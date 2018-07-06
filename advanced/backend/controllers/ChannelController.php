<?php
namespace backend\controllers;
use backend\models\Goodssku;
use backend\models\OaGoods;
use backend\models\OaGoodsinfo;
use backend\models\OaWishgoodssku;
use backend\unitools\PHPExcelTools;
use common\components\BaseController;
use Yii;
use backend\models\Channel;
use backend\models\OaTemplatesVar;
use backend\models\OaTemplates;
use backend\models\ChannelSearch;
use backend\models\WishSuffixDictionary;
use backend\models\OaWishgoods;
use backend\models\Wishgoodssku;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;

/**
 * ChannelController implements the CRUD actions for Channel model.
 */
class ChannelController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Channel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ChannelSearch();
        $params = Yii::$app->request->queryParams;
        $selectedStatus = $params? $params['ChannelSearch']['completeStatus']:[];
        $dataProvider = $searchModel->search($params,'channel','平台信息');
        $connection = Yii::$app->db;
        $jooms = $connection->createCommand('select joomName from oa_joom_suffix ORDER BY joomName')->queryAll();
        $joomAccount = \array_map(function ($arr) { return $arr['joomName'];}, $jooms);


        //获取商品状态列表
        $sql = 'SELECT DictionaryName FROM B_Dictionary WHERE CategoryID=15 ORDER BY FitCode ASC ';
        $res = $connection->createCommand($sql)->queryAll();
        $list = ArrayHelper::map($res,'DictionaryName','DictionaryName');
        $stores = $this->getStore();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'goodsStatusList' => $list,
            'joomAccount' => $joomAccount,
            'stores' => $stores,
            'selectedStatus' =>\implode(',',$selectedStatus)
        ]);
    }

    /**
     * Displays a single Channel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Channel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Channel();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->pid]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Channel model.Default wish.
     * If update is successful, the browser will be redirected to the 'editwish' page.
     * @param integer $id .
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id)
    {

        $sku = OaWishgoods::find()->where(['infoid' => $id])->all();
        if (!$sku) {
            throw new NotFoundHttpException("The product was not found.");
        }
        $post = Yii::$app->request->post();
        unset($post['OaWishgoods']['stockUp']);
        if ($sku[0]->load($post)) {
            $dataPost = $_POST;

            $sku[0]['main_image'] = $dataPost['main_image'];
            unset($sku[0]['extra_images']);
            foreach ($dataPost["extra_images"] as $key => $value) {
                $sku[0]['extra_images'] .= $value . "\n";

            }
            $sku[0]['extra_images'] = rtrim($sku[0]['extra_images'], "\n");
            if($sku[0]->update(false)) {
                return '保存成功！';
            }
            return '保存失败！';

        } else {

            $extra_images_All = explode("\n", $sku[0]['extra_images']);
            $extra_images = array_filter($extra_images_All);
            $connection = Yii::$app->db;
            $jooms = $connection->createCommand('select joomName from oa_joom_suffix ORDER BY joomName')->queryAll();
            $joomAccount = \array_map(function ($arr) { return $arr['joomName'];}, $jooms);
            return $this->render('editwish', [
                'extra_images' => $extra_images,
                'sku' => $sku[0],
                'joomAccount' => $joomAccount

            ]);
        }
    }


    /*
     * 多属性信息
     */
    public function actionVariations($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Wishgoodssku::find()->where(['pid' => $id])->orderBy('sid'),
            'pagination' => [
                'pageSize' => 200,

            ],
        ]);
        return $this->renderAjax('variations', [
            'dataProvider' => $dataProvider,
            'id' => $id,
        ]);

    }

    /**
     * Updates an existing Channel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateEbay($id)
    {
        $templates = OaTemplates::find()->where(['infoid' => $id])->one();
        if (Yii::$app->request->isPost) {

        } else {
            $connection = yii::$app->db;
            $ebay_sql = 'select ebayName,ebaySuffix from oa_ebay_suffix  ';
            $store_sql = 'select DISTINCT(storeCountry) as storeCountry from oa_ebay_suffix  ';
            $ebay_account = $connection->createCommand($ebay_sql)->queryAll();
            $stores = $connection->createCommand($store_sql)->queryAll();
            $ebay_stores = ArrayHelper::getColumn($stores, 'storeCountry');
            //封装成key-value
            $ebay_map = [];
            foreach ($ebay_account as $row) {
                $ebay_map[$row['ebayName']] = $row['ebaySuffix'];
            }

            //查找站点对应的货币符号
            $site = $templates->site;
            $currency_sql = "select isnull(currencyCode,'USD') as currencyCode from oa_ebay_country where code=$site";
            $currency_ret = $connection->createCommand($currency_sql)->queryOne();

            //加载物流信息
            $inShippingService1 = $this->actionShipping('InFir', $templates->site, false);
            $inShippingService2 = $this->actionShipping('InSec', $templates->site, false);
            $OutShippingService = $this->actionShipping('OutFir', $templates->site, false);
            return $this->render('editEbay', [
                'templates' => $templates,
                'infoId' => $id,
                'inShippingService1' => $inShippingService1,
                'inShippingService2' => $inShippingService2,
                'outShippingService' => $OutShippingService,
                'ebayAccount' => $ebay_map,
                'ebayStores' => $ebay_stores,
                'currencyCode' =>$currency_ret['currencyCode'],
            ]);
        }

    }

    /**
     *
     *
     * ebay基本信息保存
     * @param $id
     */

    public function actionEbaySave($id)
    {
        $template = OaTemplates::find()->where(['nid' => $id])->one();
        $data = $_POST['OaTemplates'];
        unset($data['stockUp']);
        //设置默认物流
        try {
            $template->setAttributes($data, true);
            if ($template->save(false)) {
                echo "保存成功";
            } else {
                echo "保存失败";
            }
        } catch (\Exception $ex) {
            echo $ex;
        }

    }

    /**
     * ebay 完善模板
     * @param $id
     * @param $infoId
     */

    public function actionEbayComplete($id, $infoId)
    {
        $template = OaTemplates::find()->where(['nid' => $id])->one();
        $info = OaGoodsinfo::find()->where(['pid' => $infoId])->one();
        $data = $_POST['OaTemplates'];
        unset($data['stockUp']);
        //设置默认物流
        try {
            $template->setAttributes($data, true);

            //动态计算产品的状态
            $old_status = $info->completeStatus?$info->completeStatus:'';
            $status = str_replace('|eBay已完善', '', $old_status);
            $complete_status = $status . '|eBay已完善';
            $info->completeStatus = $complete_status;
            if ($template->update(true) && $info->save(false)) {
                echo "保存成功";
            } else {
                echo "2保存失败";
            }
        } catch (\Exception $ex) {
            echo "1保存失败";
        }
    }

    /**
     * @brief 多属性保存
     * @param $id
     */
    public function actionVarSave($id)
    {
        $varData = $_POST['OaTemplatesVar'];
        $pictureKey = $_POST['picKey'];
        $labels = json_decode($_POST['label'], true);
        $var = new OaTemplatesVar();
        $fields = $var->attributeLabels();
        //获取动态列的表名
        $old_labels = [];
        foreach (current($varData) as $key => $value) {
            if (!in_array($key, array_keys($fields))) {
                array_push($old_labels, $key);
            }
        }
        //列名映射成真实列名
        $labels_map = array_combine($old_labels, $labels);
        //保存数据
        $row = [];
        foreach ($varData as $key => $value) {
            $value['tid'] = $id;
            //动态生成property列的值
            $property = ['columns' => [], 'pictureKey' => $pictureKey];
            foreach ($value as $field => $val) {

                if (in_array($field, array_keys($fields))) {
                    $row[$field] = $val;
                } else {
                    array_push($property['columns'], [$labels_map[$field] => $val]);
                }
            }
            $row['property'] = json_encode($property);
            if (strpos($key, 'New') === false) {
                //update
                $ret = $this->findVar($key);
                $ret->setAttributes($row);
                $ret->save(false);
            } else {
                //create
                $model = new OaTemplatesVar();
                $model->setAttributes($row);
                if ($model->save(false)) {

                } else {
                    echo "Wrong!";
                }
            }

        }
        echo "保存成功！";
        //根据varId的值，来决定更新还是创建

    }

    /**
     * 多属性设置页面
     * @param $id
     * @return mixed
     */

    public function actionTemplatesVar($id)
    {
        $templatesVar = new ActiveDataProvider([
            'query' => OaTemplatesVar::find()->where(['tid' => $id]),
            'pagination' => [
                'pageSize' => 150,
            ],
        ]);
        $propertyVar = OaTemplatesVar::find()->where(['tid' => $id])->all();
        $columns = [];
        foreach ($propertyVar as $row) {
            $pro = json_decode($row->property, true);
            $columns['pictureKey'] = $pro['pictureKey'];
            $col = $pro['columns'];
            foreach ($col as $ele) {
                foreach ($ele as $key => $value) {
                    if (array_key_exists($key, $columns)) {
                        array_push($columns[$key], $value);
                    } else {
                        $columns[$key] = [$value];
                    }
                }
            }
        }
        return $this->renderAjax('templatesVar', [
            'templatesVar' => $templatesVar,
            'tid' => $id,
            'propertyVar' => $propertyVar,
            'columns' => $columns,
        ]);
    }

    /**
     * delete row from templatesVar
     * @return mixed
     */

    public function actionDeleteVar()
    {
        $id = $_POST["id"];

        // 根据id的类型来执行不同的操作
        if (is_array($id)) {
            foreach ($id as $row) {
                $this->findVar($row)->delete();
            }

        } else {
            $this->findVar($id)->delete();
        }
    }


    /**
     * Deletes an existing Channel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = OaGoodsinfo::findOne(['pid' => $id]);
        if($model && empty($model->completeStatus)){
            try{
                $this->findModel($id)->delete();//OaGoodsinfo信息删除
                OaGoods::deleteAll(['nid' => $id]);//OaGoods信息删除
                Goodssku::deleteAll(['pid' => $id]);//Goodssku信息删除

                OaTemplates::deleteAll(['infoid' => $id]);//eBay平台信息删除
                OaTemplatesVar::deleteAll(['pid' => $id]);//eBay平台SKU信息删除
                OaWishgoods::deleteAll(['infoid' => $id]);//Wish平台信息删除
                OaWishgoodssku::deleteAll(['pid' => $id]);//Wish平台SKU信息删除

            }
            catch (Exception $e){
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * exists or not
     * @param $id
     * @return mixed
     */
    protected function findVar($id)
    {
        $model = OaTemplatesVar::find()->where(['nid' => $id])->one();
        if (!empty($model)) {
            return $model;
        } else {
            return false;
        }
    }

    /**
     * Finds the Channel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Channel the loaded model
     * @throws /NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Channel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     *  返回物流名称
     * @param $type
     * @param site_id
     * @return nothing
     */
    public function actionShipping($type, $site_id, $isJson = true)
    {
        $sql = "select oss.nid as nid,servicesName,currencyCode from oa_shippingService as oss 
                LEFT JOIN  oa_ebay_country as oec on  oss.siteId=oec.code
                where oss.type = '{$type}' and oss.siteId='{$site_id}'";
        $connection = Yii::$app->db;
        $ret = $connection->createCommand($sql)->queryAll();
        return $isJson ? json_encode($ret) : $ret;
    }

    /**
     * @brief ebay模板导出时多余的字段维护在一个数组中
     */
    private $extra_fields = ['nameCode', 'specifics'];//因其他需要返回的字段




    /**
     * @brief 导出ebay模板
     * @param $id
     * @param $accounts
     */
    public function actionExportEbay($id, $accounts = '')
    {

        $sql = "oa_P_ebayTemplates {$id},'{$accounts}'";
        $db = yii::$app->db;
        $query = $db->createCommand($sql);
        $ret = $query->queryAll();
        $code_sql = "select ofo.goodsCode from oa_templates as ots 
                      LEFT  JOIN oa_goodsinfo as ofo 
                      on ots.infoid=ofo.pid where ots.nid=$id";
        if (empty($ret)) {
            return;
        }
        $code_ret = $db->createCommand($code_sql)->queryOne();
        $goods_code = $code_ret['goodsCode'];
        $objPHPExcel = new \PHPExcel();
        $sheetNumber = 0;
        $objPHPExcel->setActiveSheetIndex($sheetNumber);
        $sheetName = 'ebay模板';
        $objPHPExcel->getActiveSheet()->setTitle($sheetName);

        header('Content-Type: application/vnd.ms-excel');
        $fileName = $goods_code . "-eBay模板-" . date("d-m-Y-His") . ".xls";
        header('Content-Disposition: attachment;filename=' . $fileName . ' ');
        header('Cache-Control: max-age=0');


        //获取列名&设置image字段
        $firstRow = $ret[0];
        //过滤掉多余字段
        $tabFields = array_filter(array_keys($firstRow), function ($item) {
            return !in_array($item, $this->extra_fields);
        });
        // 设置变体
        $checkSql = "select isnull(count(*),0) as skuNumber from oa_templates as ots left join 
                oa_templatesvar as otr on ots.nid=otr.tid where otr.tid={$id}";
        $flag = $db->createCommand($checkSql)->queryone();
        $count = intval($flag['skuNumber']);
        if ($count > 1) {
            $findSql = "select *,otr.sku as varSku,otr.quantity as varQuantity from oa_templates as ots left join 
                oa_templatesvar as otr on ots.nid=otr.tid where otr.tid={$id}";
            $allRows = $db->createCommand($findSql)->queryAll();
            $picKey = json_decode($allRows[0]['property'], true)['pictureKey'];
            $columns = json_decode($allRows[0]['property'], true)['columns'];
            $extraPage = json_decode($allRows[0]['extraPage'], true)['images'];
            $picCount = count($extraPage);

            //设置属性名
            $variationSpecificsSet = ['NameValueList' => []];
            foreach ($columns as $col) {
                $map = ['Name' => array_keys($col)[0], 'Value' => array_values($col)[0]];
                array_push($variationSpecificsSet['NameValueList'], $map);
            }
        }


        // 写入列名
        foreach ($tabFields as $num => $name) {
            $objPHPExcel->getActiveSheet()->setCellValue(PHPExcelTools::stringFromColumnIndex($num) . '1', $name);
        }

        $data =  $this->actionNameTags($id,'oa_templates');
        //写入单元格值
        $title_list = []; //存放已生成标题的标题池。
        foreach ($ret as $rowNum => $row) {
            if ($count > 1) {
                $var = $this->getVariations($count, $allRows, $picKey, $picCount, $row['nameCode']);
                $row['Variation'] = json_encode($var);
            }
            //Title
            $names = '';
            while(true){
                $title = $this->actionNonOrder($data,'eBay');
                if(!in_array($title,$title_list)||empty($title)){
                    $name = $title;
                    array_push($title_list,$title);
                    break;
                }
            }

            $row['Title'] = $name;
            //specifics 重新赋值
            $specifics = json_decode($row['specifics'], true)['specifics'];
            foreach ($specifics as $index => $map) {
                $key = array_keys($map)[0];
                $value = array_values($map)[0];
                $row['Specifics' . strval($index + 1)] = $key . ':' . $value;
            }

            foreach ($tabFields as $num => $name) {
                $objPHPExcel->getActiveSheet()->setCellValue(PHPExcelTools::stringFromColumnIndex($num) . ($rowNum + 2), $row[$name]);
            }
        }

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }


    /**
     * @brief 封装多属性的内部方法
     * @param $count , sku计数
     * @param $allRows ,
     * @param $picKey ,
     * @param $picCount
     * @param $accountName
     * @return array $var
     */
    private function getVariations($count, $allRows, $picKey, $picCount, $accountName)
    {
        if ($count <= 1) {
            return false;
        }
        //设置图片&//设置变体
        $pictures = [];
        $variation = [];
        foreach ($allRows as $row) {
            $variationSpecificsSet = ['NameValueList' => []];
            $columns = json_decode($row['property'], true)['columns'];
            $value = ['value' => ''];
            foreach ($columns as $col) {
                if (array_keys($col)[0] == $picKey) {
                    $value['value'] = $col[$picKey];
                    break;
                }
            }
            foreach ($columns as $col) {
                $map = ['Name' => array_keys($col)[0], 'Value' => array_values($col)[0]];
                array_push($variationSpecificsSet['NameValueList'], $map);
            }
            $pic = ['VariationSpecificPictureSet' => ['PictureURL' => [$row['imageUrl']]], 'Value' => $value['value']];
            array_push($pictures, $pic);
            $var = [
                'SKU' => $row['varSku'] . $accountName,
                'Quantity' => $row['varQuantity'],
                'StartPrice' => $row['retailPrice'],
                'VariationSpecifics' => $variationSpecificsSet,

            ];
            array_push($variation, $var);
        }

        $var = [
            'assoc_pic_key' => $picKey,
            'assoc_pic_count' => $picCount,
            'Variation' => $variation,
            'Pictures' => $pictures,
            'VariationSpecificsSet' => $variationSpecificsSet
        ];
        return $var;
    }

    //导出数据 wish平台
    public function actionExport($id)
    {
        $objPHPExcel = new \PHPExcel();
        $sheet = 0;
        $objPHPExcel->setActiveSheetIndex($sheet);
        $foos[0] = OaWishgoods::find()->where(['infoid'=>$id])->all();
        $sql = ' SELECT cate FROM oa_goods WHERE nid=(SELECT goodsid FROM oa_goodsinfo WHERE pid='.$id.')';
        $back_sql = ' select categoryParentName as cate from b_goods as bgs LEFT  JOIN b_goodsCats as bc on bgs.goodscategoryId = bc.nid where bgs.nid=(SELECT Bgoodsid FROM oa_goodsinfo WHERE pid='.$id.')';

        $db = yii::$app->db;
        $query = $db->createCommand($sql);
        $cate = $query->queryAll();
        if(empty($cate)){
            $query = $db->createCommand($back_sql);
            $cate = $query->queryAll();
        }
        $sql_GoodsCode = 'select GoodsCode,isVar from oa_goodsinfo WHERE pid='.$id;
        $dataGoodsCode = $db->createCommand($sql_GoodsCode)
                        ->queryAll();
        $GoodsCode = $dataGoodsCode[0]['GoodsCode'];
        $isVar = $dataGoodsCode[0]['isVar'];

        $columnNum = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P'];
        $colName = [
            'sku', 'selleruserid', 'name', 'inventory', 'price', 'msrp', 'shipping', 'shipping_time', 'main_image', 'extra_images',
            'variants', 'landing_page_url', 'tags', 'description', 'brand', 'upc'];
        $combineArr = array_combine($columnNum, $colName);
        $sub = 1;
        foreach ($columnNum as $key => $value) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($value)->setWidth(20);
            $objPHPExcel->getActiveSheet()->getStyle($value . $sub)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->setTitle($foos[0][0]['SKU'])
                ->setCellValue($value . $sub, $combineArr[$value]);
        }
        $suffixAll = WishSuffixDictionary::find()
            ->asArray()
            ->where("ParentCategory like :cate")
            ->orWhere("ParentCategory is null")
            ->addParams([':cate' => '%' . $cate[0]['cate'] . '%'])
            ->all();
        $data =  $this->actionNameTags($id,'oa_wishgoods');

        $title_list = [];
        foreach($suffixAll as $key=>$value){
            //标题关键字
            while(true){
                $title = $this->actionNonOrder($data,'Wish');
                if(!in_array($title,$title_list)||empty($title)){
                    $name = $title;
                    array_push($title_list,$title);
                    break;

                }
            }

            //价格判断
            $totalprice = ceil($foos[0][0]['price'] + $foos[0][0]['shipping']);
            if ($totalprice <= 2) {
                $foos[0][0]['price'] = 1;
                $foos[0][0]['shipping'] = 1;
            } elseif (2 < $totalprice && $totalprice <= 3) {
                $foos[0][0]['price'] = 2;
                $foos[0][0]['shipping'] = 1;
            } else {
                $foos[0][0]['shipping'] = ceil($totalprice * $value['Rate']);
                $foos[0][0]['price'] = ceil($totalprice - $foos[0][0]['shipping']);

            }
            //主图用商品编码 拼接
            if($isVar=='是'){
                $strvariant = $this->actionVariationWish($id,$value['Suffix'],$value['Rate']);
            }else{
                $strvariant = '';
            }

            $row = $key+2;
            $foos[0][0]['main_image'] = 'https://www.tupianku.com/view/full/10023/'.$GoodsCode.'-_'.$value['MainImg'].'_.jpg' ;
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$foos[0][0]['SKU'].$value['Suffix']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$value['IbaySuffix']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$name);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,$foos[0][0]['inventory']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$foos[0][0]['price']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$row,$foos[0][0]['msrp']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$row,$foos[0][0]['shipping']);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$row,'7-21');
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$row,$foos[0][0]['main_image']);
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$row,$foos[0][0]['extra_images']);
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$row,$strvariant);
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$row,'');
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$row,$foos[0][0]['wishtags']);
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$row,$foos[0][0]['description']);
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$row,'');
            $objPHPExcel->getActiveSheet()->setCellValue('P'.$row,'');

        }

        header('Content-Type: application/vnd.ms-excel');
        $filename = $foos[0][0]['SKU'] . '-Wish模版' . date("d-m-Y-His") . ".xls";
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    /*
     *生成随机标题和关键字
     *@param $id int
     * return $data array
     */

    public function actionNameTags($id,$table){

        if($table === 'oa_wishgoods') {
            $sql = ' SELECT headKeywords,requiredKeywords,randomKeywords,tailKeywords FROM ' . $table . ' WHERE infoid=' . $id;
        }
        if($table === 'oa_templates') {
            $sql = ' SELECT headKeywords,requiredKeywords,randomKeywords,tailKeywords FROM ' . $table . ' WHERE nid=' . $id;
        }
        $db = yii::$app->db;
        $query = $db->createCommand($sql);
        $words = $query->queryAll();
        if (empty($words)){
            return;
        }
        $data['head'] = $words[0]['headKeywords'];
        $data['tail'] = $words[0]['tailKeywords'];
        $data['need'] = json_decode($words[0]['requiredKeywords']);
        $data['random']= json_decode($words[0]['randomKeywords']);
        return $data;

    }

    /*
     * 乱序数组
     */
    public function actionNonOrder($data,$div){
        if($div === 'eBay'){
            $max_length = 80;
        }
        if($div === 'Wish'){
            $max_length = 110;
        }
        if($div === 'Joom'){
            $max_length = 100;
        }
        $head = [$data['head']];
        $tail = [$data['tail']];

        $need = array_filter($data['need']?:[],
            function ($ele)
            {
                if(!empty($ele)){
                    return $ele;
                }
            });
        $random = array_filter($data['random']?:[],
            function ($ele)
            {
                if(!empty($ele)){
                    return $ele;
                }
            });
        if(empty($random)||empty($need)){
            return '';
        }
        //判断固定部分的长度
        $unchanged_len = \strlen(implode(' ',array_merge($head,$need,$tail)));
        if($unchanged_len > $max_length){
            shuffle($need);
            $real_len =  implode(' ',array_merge($head,$need,$tail));
            return $real_len;
        }
        //可用长度
        $available_len = $max_length - $unchanged_len - 1;
        shuffle($random); //摇匀词库
        $random_str1 = [array_shift($random)]; //从摇匀的词库里不放回抽一个
        $random_arr = \array_slice($random,0,4);//从剩余的词库里抽四个
        $real_len = \strlen(implode(' ',array_merge($random_str1,$random_arr)));
        for($i=0;$i<4;$i++){
            if($real_len<=$available_len){
                break;
            }
            array_shift($random_arr); //去掉一个随机词
            $real_len = \strlen(implode(' ',array_merge($random_str1,$random_arr)));

        }
        shuffle($need);
        return  implode(' ',array_merge($head,$random_str1,$need,$random_arr,$tail));
    }

    /*
     * 处理多属性
     * @param $id int 商品ID
     */
    public function actionVariationWish($id, $sub, $rate)
    {

        $variants = Wishgoodssku::find()->where(['pid' => $id])->orderBy('sid')->all();
        $variation = [];
        $varitem = [];
        if (!isset($variants) || empty($variants)) {
            return;
        }


        foreach ($variants as $key => $value) {

            //价格判断
            $totalprice = ceil($value['price'] + $value['shipping']);
            if ($totalprice <= 2) {
                $value['price'] = 1;
                $value['shipping'] = 1;
            } elseif (2 < $totalprice && $totalprice <= 3) {
                $value['price'] = 2;
                $value['shipping'] = 1;
            } else {
                $value['shipping'] = ceil($totalprice * $rate);
                $value['price'] = ceil($totalprice - $value['shipping']);

            }

            $varitem['sku'] = $value['sku'] . $sub;
            $varitem['color'] = $value['color'];
            $varitem['size'] = $value['size'];
            $varitem['inventory'] = $value['inventory'];
            $varitem['price'] = $value['price'];
            $varitem['shipping'] = $value['shipping'];
            $varitem['msrp'] = $value['msrp'];
            $varitem['shipping_time'] = $value['shipping_time'];
            $varitem['main_image'] = $value['linkurl'];
            $variation[] = $varitem;
        }

        $strvariant = json_encode($variation, true);
        return $strvariant;
    }



    /*
     *编辑完成状态
     */
    public function actionWishSign($id)
    {

        $info = OaGoodsinfo::find()->where(['pid' => $id])->one();
        //动态计算产品的状态

        $old_status = $info->completeStatus?$info->completeStatus:'';
        $status = str_replace('Wish已完善', '', $old_status);
        $complete_status = 'Wish已完善'.$status;
        $info->completeStatus = $complete_status;

        $info->wishpublish = 'N';
        $ret = $info->save(false);
        if($ret){
            $this->actionUpdate($id);
            return "标记成功!";
        }
        return "标记失败!";
    }
    /**
     * 批量标记Wish已完善
     * @return mixed
     */
    public function actionWishSignLots()
    {
        $ids = yii::$app->request->post()["id"];
        $connection = yii::$app->db;
        $trans = $connection->beginTransaction();
        try{
            foreach ($ids as $id)
            {
                $model = $this->findModel($id);
                $old_status = $model->completeStatus?$model->completeStatus:'';
                $status = str_replace('Wish已完善', '', $old_status);
                $complete_status = 'Wish已完善'.$status;
                $model->completeStatus = $complete_status;
                $model->wishpublish = 'N';
                if(!$model->save(false)){
                    throw new Exception('fail to update completeStatus!');
                }
            }
            $trans->commit();
            $msg = "批量标记Wish已完善成功";
        } catch (Exception $e){
            $trans->rollBack();
            $msg = "批量标记Wish已完善失败！";
        }
        return $msg;
    }

    /*
     *编辑完成状态
     */
    public function actionJoomSign($id)
    {

        $info = OaGoodsinfo::find()->where(['pid' => $id])->one();
        //动态计算产品的状态

        $old_status = $info->completeStatus?$info->completeStatus:'';
        $status = str_replace('|Joom已完善', '', $old_status);
        $complete_status = $status.'|Joom已完善';
        $info->completeStatus = $complete_status;
        $ret = $info->save(false);
        if($ret){
            $this->actionUpdate($id);
            return "标记成功!";
        }
        return "标记失败!";
    }
    /**
     * 批量标记Joom已完善
     * @return mixed
     */
    public function actionJoomSignLots()
    {
        $ids = yii::$app->request->post()["id"];
        $connection = yii::$app->db;
        $trans = $connection->beginTransaction();
        try{
            foreach ($ids as $id)
            {
                $model = $this->findModel($id);
                $old_status = $model->completeStatus?$model->completeStatus:'';
                $status = str_replace('|Joom已完善', '', $old_status);
                $complete_status = $status.'|Joom已完善';
                $model->completeStatus = $complete_status;
                if(!$model->save(false)){
                    throw new Exception('fail to update completeStatus!');
                }
            }
            $trans->commit();
            $msg = "批量标记Joom已完善成功";
        } catch (Exception $e){
            $trans->rollBack();
            $msg = "批量标记Joom已完善失败！";
        }
        return $msg;
    }
    /**
     * 批量标记eBay已完善
     * @return mixed
     */
    public function actionEbaySignLots()
    {
        $ids = yii::$app->request->post()["id"];
        $connection = yii::$app->db;
        $trans = $connection->beginTransaction();
        try{
            foreach ($ids as $id)
            {
                $model = $this->findModel($id);
                $old_status = $model->completeStatus?$model->completeStatus:'';
                $status = str_replace('|eBay已完善', '', $old_status);
                $complete_status = $status . '|eBay已完善';
                $model->completeStatus = $complete_status;
                if(!$model->save(false)){
                    throw new Exception('fail to update completeStatus!');
                }
            }
            $trans->commit();
            $msg = "批量标记eBay已完善成功";
        } catch (Exception $e){
            $trans->rollBack();
            $msg = "批量标记eBay已完善失败！";
        }
        return $msg;
    }
    /**
     * 批量标记全部已完善
     * @return mixed
     */
    public function actionAllSignLots()
    {
        $ids = yii::$app->request->post()["id"];
        $connection = yii::$app->db;
        $trans = $connection->beginTransaction();
        try{
            foreach ($ids as $id)
            {
                $model = $this->findModel($id);
                $complete_status = 'Wish已完善|eBay已完善|Joom已完善';
                $model->completeStatus = $complete_status;
                $model->wishpublish = 'N';
                if(!$model->save(false)){
                    throw new Exception('fail to update completeStatus!');
                }
            }
            $trans->commit();
            $msg = "批量标记全部已完善成功";
        } catch (Exception $e){
            $trans->rollBack();
            $msg = "批量标记全部已完善失败！";
        }
        return $msg;
    }

    /**
     * 导出CSV文件
     * @param array $data 数据
     * @param array $header_data 首行数据
     * @param string $file_name 文件名称
     * @return string
     */
    public function actionExportCsv($data = [], $header_data = [], $file_name = '')
    {

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $file_name . '.csv');
        header('Cache-Control: max-age=0');
        $fp = fopen('php://output', 'ab');
        if (!empty($header_data)) {
            foreach ($header_data as $key => $value) {
                $header_data[$key] = iconv('utf-8', 'gbk', $value);
            }
            fputcsv($fp, $header_data);
        }
        $num = 0;
        //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 100000;
        //逐行取出数据，不浪费内存
        $count = count($data);
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $num++;
                //刷新一下输出buffer，防止由于数据过多造成问题
                if ($limit == $num) {
                    ob_flush();
                    flush();
                    $num = 0;
                }
                $row = $data[$i];

                foreach ($row as $key => $value) {
                    $row[$key] = iconv('utf-8', 'gbk', $value);
                }
                fputcsv($fp, $row);
            }
        }
        fclose($fp);
    }

    /**
     * 导出Joom
     * @param int $id 商品id
     * @param string $suffix
     */
    public function actionExportJoom($id, $suffix)
    {
        $da = $this->actionNameTags($id,'oa_wishgoods');
        $pro_name = $this->actionNonOrder($da,'Joom');
        $name = str_replace("'","''",$pro_name);
        $sql = 'P_oa_toSecJoom @pid=' . $id.",@name='".$name."',@joom=".$suffix;
        $adjust_sql  = 'select greater_equal,less,added_price from oa_joom_wish';
        $db = yii::$app->db;
        $query = $db->createCommand($sql);
        $joomRes = $query->queryAll();
        $adjust_ret = $db->createCommand($adjust_sql)->queryAll();
        if (empty($joomRes)) {
            return;
        }

        // adjust price according to weight
        $filter_ret = [];
        foreach ($joomRes as $joom) {
            $weight = $joom['Shipping weight'] * 1000 ;
            foreach ($adjust_ret as $adjust) {
                if ($weight >= $adjust['greater_equal'] && $weight <  $adjust['less'] ) {
                    $joom['*Price'] += $adjust['added_price'] ;
                    break;
                }
            }
            $filter_ret[] = $joom;
        }
        $header_data = array_keys($joomRes[0]);
        $file_name = $joomRes[0]['Parent Unique ID'] . '-Joom模板csv';
        $this->actionExportCsv($filter_ret, $header_data, $file_name);

    }

    /**
     * 导出Joom2
     * @param int $id 商品id
     */
    public function actionExportSecJoom($id)
    {
        $da = $this->actionNameTags($id,'oa_wishgoods');
        $name = $this->actionNonOrder($da,'Joom');
        $name = str_replace("'","''",$name);
        $sql = 'P_oa_toSecJoom @pid=' . $id.",@name='".$name."'";
        $adjust_sql  = 'select greater_equal,less,added_price from oa_joom_wish';
        $db = yii::$app->db;
        $query = $db->createCommand($sql);
        $joomRes = $query->queryAll();
        $adjust_ret = $db->createCommand($adjust_sql)->queryAll();
        if (empty($joomRes)) {
            return;
        }

        // adjust price according to weight
        $filter_ret = [];
        foreach ($joomRes as $joom) {
            $weight = $joom['Shipping weight'] * 1000 ;
            foreach ($adjust_ret as $adjust) {
                if ($weight >= $adjust['greater_equal'] && $weight <  $adjust['less'] ) {
                    $joom['*Price'] += $adjust['added_price'] ;
                    break;
                }
            }
            $filter_ret[] = $joom;
        }
        $header_data = array_keys($joomRes[0]);
        $file_name = $joomRes[0]['Parent Unique ID'] . '-Joom2模板csv';
        $this->actionExportCsv($filter_ret, $header_data, $file_name);

    }

    /**
     * 批量导出Joom
     * @param string $pids
     * @param string $suffxi
     *
     */
    public function actionExportLotsJoom($pids, $suffix)
    {
        if(empty($pids)){
            $this->actionExportCsv([], [], 'none');
            return;
        }
        $pids = explode(',', $pids);
        $procedur = 'P_oa_toVarJoom ';
        $filter_ret = [];
        $header = [];
        foreach ($pids as $pid) {
            $da = $this->actionNameTags($pid,'oa_wishgoods');
            $name = $this->actionNonOrder($da,'Joom');
            $name = str_replace("'","''",$name);
            $sql = $procedur.'@pid=' . $pid.",@name='".$name."',@joom=".$suffix;
            $adjust_sql  = 'select greater_equal,less,added_price from oa_joom_wish';
            $db = yii::$app->db;
            $query = $db->createCommand($sql);
            $joomRes = $query->queryAll();
            $adjust_ret = $db->createCommand($adjust_sql)->queryAll();
            if (empty($joomRes)) {
                return;
            }

            // adjust price according to weight

            foreach ($joomRes as $joom) {
                $weight = $joom['Shipping weight'] * 1000 ;
                foreach ($adjust_ret as $adjust) {
                    if ($weight >= $adjust['greater_equal'] && $weight <  $adjust['less'] ) {
                        $joom['*Price'] += $adjust['added_price'] ;
                        break;
                    }
                }
                $filter_ret[] = $joom;
            }
            $header['header'] = array_keys($joomRes[0]);
        }
        $file_name = date('Y-m-d') . '-Joom模板csv';
        $this->actionExportCsv($filter_ret, $header['header'], $file_name);


    }
    /**
     * @brief ebay简称,仓储国家二级联动
     * @param string $store
     * @return mixed
     */
    public  function  actionStoreCountry($store)
    {
        $sql = 'select  ebaySuffix from oa_ebay_suffix where storeCountry=:store';
        $query = Yii::$app->db->createCommand($sql,[':store'=>$store]);
        if (empty($store)) {
            $sql = 'select  ebaySuffix from oa_ebay_suffix';
            $query = Yii::$app->db->createCommand($sql);
        }
        $ret = ArrayHelper::getColumn($query->queryAll(),'ebaySuffix');
        return json_encode($ret);

    }
    /**
     * 导出Shopee
     * @param int $id 商品id
     */
    public function actionExportShopee($id)
    {
        $objPHPExcel = new \PHPExcel();
        $sheet = 0;
        $objPHPExcel->setActiveSheetIndex($sheet);
        $foos[0] = OaWishgoods::find()->where(['infoid'=>$id])->all();
        $sql = ' SELECT cate FROM oa_goods WHERE nid=(SELECT goodsid FROM oa_goodsinfo WHERE pid='.$id.')';

        $db = yii::$app->db;
        $query = $db->createCommand($sql);
        $cate = $query->queryAll();

        $sql_GoodsCode = 'select GoodsCode,isVar from oa_goodsinfo WHERE pid='.$id;
        $dataGoodsCode = $db->createCommand($sql_GoodsCode)->queryAll();
        $GoodsCode = $dataGoodsCode[0]['GoodsCode'];
        $isVar = $dataGoodsCode[0]['isVar'];

        $columnNum = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q'];
        //$colName = ['sku', 'selleruserid', 'name', 'inventory', 'price', 'msrp', 'shipping', 'shipping_time', 'main_image', 'extra_images',
        //    'variants', 'landing_page_url', 'tags', 'description', 'brand', 'upc'];
        $colName = ['mubanid', 'selleruserid', 'categoryid', 'name', 'sku', 'price', 'stock', 'weight', 'package_width', 'package_length', 'package_height',
            'shipdays', 'imgurl', 'description', 'attributes', 'logistics', 'variation'];
        $combineArr = array_combine($columnNum, $colName);
        //var_dump($foos[0]);exit;
        $sub = 1;
        foreach ($columnNum as $key => $value) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($value)->setWidth(20);
            $objPHPExcel->getActiveSheet()->getStyle($value . $sub)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->setTitle($foos[0][0]['SKU'])
                ->setCellValue($value . $sub, $combineArr[$value]);
        }
        $suffixAll = WishSuffixDictionary::find()
            ->asArray()
            ->where("ParentCategory like :cate")
            ->orWhere("ParentCategory is null")
            ->addParams([':cate' => '%' . $cate[0]['cate'] . '%'])
            ->all();
        //var_dump($suffixAll);exit;
        //获取关键字
        $data =  $this->actionNameTags($id,'oa_wishgoods');
        $title_list = [];
        foreach($suffixAll as $key=>$value){
            //标题关键字
            while(true){
                $title = $this->actionNonOrder($data,'Wish');
                if(!in_array($title,$title_list)||empty($title)){
                    $name = $title;
                    array_push($title_list,$title);
                    break;
                }
            }

            //价格判断
            $totalprice = ceil($foos[0][0]['price'] + $foos[0][0]['shipping']);
            if ($totalprice <= 2) {
                $foos[0][0]['price'] = 1;
                $foos[0][0]['shipping'] = 1;
            } elseif (2 < $totalprice && $totalprice <= 3) {
                $foos[0][0]['price'] = 2;
                $foos[0][0]['shipping'] = 1;
            } else {
                $foos[0][0]['shipping'] = ceil($totalprice * $value['Rate']);
                $foos[0][0]['price'] = ceil($totalprice - $foos[0][0]['shipping']);

            }
            //主图用商品编码 拼接
            if($isVar=='是'){
                $strvariant = $this->actionVariationWish($id,$value['Suffix'],$value['Rate']);
            }else{
                $strvariant = '';
            }
            //var_dump($foos[0][0]);
            //var_dump($value);exit;
            $row = $key+2;
            $foos[0][0]['main_image'] = 'https://www.tupianku.com/view/full/10023/'.$GoodsCode.'-_'.$value['MainImg'].'_.jpg' ;
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$foos[0][0]['SKU']);                  //模板ID SKU
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$value['IbaySuffix']);              //卖家简称
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row,$value['ParentCategory']);            //分类
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$row,$name);                               //商品名称
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$foos[0][0]['SKU'].$value['Suffix']); //唯一SKU
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$row,$foos[0][0]['price']);                 //价格 price
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$row,$foos[0][0]['shippingtime']);             //stock
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$row,'7-21');                              //重量
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$row,5);                                   //包裹宽度
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$row,5);                                   //包裹长度
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$row,5);                                   //包裹高度
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$row,'7-21');                              //shipdays 7-21天
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$row,$foos[0][0]['main_image']);           // 图片imgurl  ？主图
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$row,$foos[0][0]['description']);          //描述
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$row,'');                                  //attributes属性？
            $objPHPExcel->getActiveSheet()->setCellValue('P'.$row,'');                                  //logistics ？物流
            $objPHPExcel->getActiveSheet()->setCellValue('Q'.$row,$strvariant);                         //variation

        }

        header('Content-Type: application/vnd.ms-excel');
        $filename = $foos[0][0]['SKU'] . '-Shopee模版' . date("YmdHis") . ".xls";
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    private function getStore()
    {
        $db = Yii::$app->db;
        $sql = 'SELECT StoreName as store from B_store';
        $query = $db->createCommand($sql)->queryAll();
        $ret = ArrayHelper::getColumn($query,'store');
        return \array_combine($ret,$ret);


    }
}
