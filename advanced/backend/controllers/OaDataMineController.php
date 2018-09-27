<?php

namespace backend\controllers;

use backend\unitools\PHPExcelTools;
use common\components\BaseController;
use Yii;
use app\models\OaDataMine;
use app\models\OaDataMineSearch;
use app\models\OaDataMineDetail;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\data\ActiveDataProvider;
/**
 * OaDataMineController implements the CRUD actions for OaDataMine model.
 */
class OaDataMineController extends BaseController
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
                    'delete' => ['POST','GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all OaDataMine models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OaDataMineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $cat_sql  = 'select CategoryName from B_GoodsCats where CategoryLevel=1';
        $sub_cat_sql = 'select CategoryName from B_GoodsCats where CategoryLevel=2';
        $db = Yii::$app->db;
        $cat = $db->createCommand($cat_sql)->queryAll();
        $cat = array_map(function ($arr){return $arr['CategoryName'];}, $cat);
        $sub_cat = $db->createCommand($sub_cat_sql)->queryAll();
        $sub_cat = array_map(function ($arr){return $arr['CategoryName'];}, $sub_cat);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'cat' => $cat,
            'subCat' =>$sub_cat
        ]);
    }

    /**
     * Displays OaDataMineDetail.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $mine = OaDataMine::findOne(['id' => $id]);
        return $this->render('view', [
            'model' => $mine,
        ]);
    }


    /**
     * @brief mine detail
     * @return mixed
     */
    public function actionMineDetail($id='')
    {

        $response=Yii::$app->response;
        $response->format=Response::FORMAT_JSON;
        $query = OaDataMineDetail::find();
        $query->select([
            'id','childId','color','proSize',
            'quantity','price','msrPrice','shipping',
            'shippingWeight','shippingTime','varMainImage'
        ]);
        $query->andWhere(['mid' => $id]);
        return $query->all();

    }

    /**
     * Creates a new OaDataMine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OaDataMine();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OaDataMine model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $query = OaDataMineDetail::find()->joinWith('oa_data_mine');
        $query->select('oa_data_mine_detail.*,oa_data_mine.cat,oa_data_mine.subCat,oa_data_mine.spAttribute');
        $query->where(['mid'=>$id]);
        $mine = $query->one();
        $cat_sql  = 'select CategoryName from B_GoodsCats where CategoryLevel=1';
        $sub_cat_sql = 'select CategoryName from B_GoodsCats where CategoryParentName=:cat ';
        $db = Yii::$app->db;
        $cat = $db->createCommand($cat_sql)->queryAll();
        $cat = array_map(function ($arr){return $arr['CategoryName'];}, $cat);
        $sub_cat = $db->createCommand($sub_cat_sql,['cat'=>$mine->cat])->queryAll();
        $sub_cat = array_map(function ($arr){return $arr['CategoryName'];}, $sub_cat);
        return $this->render('show-basic', [
            'mine' => $mine,
            'mid' => $id,
            'cat' => $cat,
            'subCat' => $sub_cat,
        ]);
    }


    /**
     * @brief detail to edit
     * @param $mid int
     * @return mixed
     */
    public function actionDetail($mid)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => OaDataMineDetail::find()->where(['mid' => $mid])->orderBy('id'),
            'pagination' => [
                'pageSize' => 200,

            ],
        ]);
        return $this->renderAjax('show-detail',[
            'dataProvider' => $dataProvider,
            'mid' => $mid

        ]);
    }

    /**
     * @brief detail to bind
     * @param $id int
     * @return mixed
     */
    public function actionBind($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => OaDataMineDetail::find()->where(['mid' => $id])->orderBy('id'),
            'pagination' => [
                'pageSize' => 200,

            ],
        ]);
        $mine = OaDataMine::findOne(['id'=>$id]);
        $py_code = $mine->pyGoodsCode;
        $goods_code = $mine->goodsCode;
        return $this->renderAjax('bind-detail',[
            'dataProvider' => $dataProvider,
            'mid' => $id,
            'goods_code' => $goods_code,
            'py_code' =>$py_code

        ]);
    }

    /**
     * @brief save bind detail
     * @param  $mid int
     * @param  $code string
     * @return mixed
     * @throws \Exception
     */
    public function actionSaveBindDetail($mid,$code)
    {
        $post = Yii::$app->request->post();
        $details = $post['OaDataMineDetail'];
        $con = Yii::$app->db;
        $mine_sql = 'update Oa_data_Mine set pyGoodsCode=:code,devStatus=:sta where id=:id';
        $detail_sql = 'update Oa_Data_Mine_Detail set pySku=:sku where id=:id';
        $bind_sql = 'insert Into B_goodsSKULinkShop (sku,ShopSKU) VALUES (:sku,:childId)';
        $update_sql = 'update B_goodsSKULinkShop set sku=:sku where ShopSKU=:childId';
        $trans = $con->beginTransaction();
        try{
            foreach ($details as $key=>$row)
            {
                $update_ret = $con->createCommand($update_sql,[':sku' => $row['pySku'],':childId' => $row['childId']])->execute();
                if($update_ret === 0) {
                    $con->createCommand($bind_sql,[':sku' =>$row['pySku'],':childId'=>$row['childId']])->execute();
                }
                $detail_ret = $con->createCommand($detail_sql,[':sku' =>$row['pySku'],':id'=>$key])->execute();
                if(!$detail_ret) {
                  throw new \Exception('关联失败！');
                }
            }

            $status_ret = $con->createCommand($mine_sql,[':code' =>$code,':sta'=>'已关联',':id'=>$mid])->execute();
            if(!$status_ret) {
                throw new \Exception('关联失败！');
            }
            $trans->commit();
            $msg='关联成功！';
        }
        catch (\Exception $why){
            $trans->rollBack();
            $msg='关联失败！';
        }
        return $msg;
    }

    /**
     * Deletes an existing OaDataMine model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        OaDataMineDetail::deleteAll(['mid' => $id]);
        return $this->redirect(['index']);
    }

    /**
     * create crawl job
     * @return mixed
     */
    public function actionCreateJob()
    {
        try
        {
            $request = Yii::$app->request->post();
            $db = Yii::$app->db;
            $max_code_sql = 'select goodsCode from oa_data_mine 
                        where datediff(d,createTime,getdate())=0 
                        and id =(select max(id) from oa_data_mine 
                        where datediff(d,createTime,getdate())=0 )';
            $platform = $request['platform'];
            $creator = Yii::$app->user->identity->username;
            $max_code = $db->createCommand($max_code_sql)->queryOne()['goodsCode']?? 'A'.date('Ydm').'0000';
            $jobs= explode(',',$request['proId']);
            $trans = $db->beginTransaction();
            try{
                foreach ($jobs as $pro_id){
                    $job_model = new OaDataMine();
                    $pro_id = trim($pro_id);
                    $goods_code = $this->generateCode($max_code);
                    $current_time = date('Y-m-d H:i:s');
                    $job_model->proId = $pro_id;
                    $job_model->platForm = $platform;
                    $job_model->creator = $creator;
                    $job_model->createTime = $current_time;
                    $job_model->updateTime = $current_time;
                    $job_model->progress = '待采集';
                    $job_model->goodsCode = $goods_code;
                    $job_model->detailStatus = '未完善';
                    if($job_model->save()){
                        $job_id = $job_model->id;
                        $redis = Yii::$app->redis;
                        $redis->lpush('job_list',$job_id.','.$pro_id);
                        $max_code =  $goods_code;
                    }
                    else {
                        throw  new \Exception("fail to save");
                    }

                }
                $trans->commit();
                $msg = '任务添加成功！';
            }
            catch (\Exception $why){
                $trans->rollBack();
                $msg = '任务添加失败！';
            }

        }
        catch (\Exception $why){
            $msg = '任务添加失败！';
        }
        return json_encode(['msg' =>$msg]);
    }

    /**
     * @brief export csv
     * @param @mid int
     */
    public function actionExport($mid)
    {
        $db = Yii::$app->db;
        $sql = "select odm.parentId,odm.proName,odm.description,odm.tags,
                odm.childId,odm.color,odm.proSize,odm.quantity,odm.price,odm.msrPrice,
                odm.shipping,odm.shippingWeight,odm.shippingTime,odm.MainImage,odm.varMainImage,
                extra_image1,extra_image2,extra_image3,
                extra_image4,extra_image5,extra_image6,extra_image7,
                extra_image8,extra_image9,extra_image10,'' as extra_image0,
                CASE
                WHEN isnull(isLiquid, 0) = 1 THEN
                    'liquid'
                ELSE
                    CASE
                WHEN isnull(IsPowder, 0) = 1 THEN
                    'powder'
                ELSE
                    CASE
                WHEN isnull(isMagnetism, 0) = 1 THEN
                    'withBattery'
                ELSE
                    CASE
                WHEN isnull(IsCharged, 0) = 1 THEN
                    'withBattery'
                ELSE
                    'notDangerous'
                END
                END
                END
                End  AS 'Dangerous Kind'
                from oa_data_mine_detail as odm left join oa_data_mine as om
                on om.id=odm.mid
                where mid=:mid";
        $query = $db->createCommand($sql ,[':mid' => $mid]);
        $ret = $query->queryAll();
        $heard_name = [
            'Parent Unique ID',
            '*Product Name',
            'Description',
            '*Tags',
            '*Unique ID',
            'Color',
            'Size',
            '*Quantity',
            '*Price',
            '*MSRP',
            '*Shipping',
            'Shipping weight',
            'Shipping Time(enter without " ", just the estimated days )',
            '*Product Main Image URL',
            'Variant Main Image URL',
            'Extra Image URL',
            'Extra Image URL 1',
            'Extra Image URL 2',
            'Extra Image URL 3',
            'Extra Image URL 4',
            'Extra Image URL 5',
            'Extra Image URL 6',
            'Extra Image URL 7',
            'Extra Image URL 8',
            'Extra Image URL 9',
            'Extra Image URL 10',
            'Dangerous Kind'
        ];

        $excel = new \PHPExcel();
        $sheet_num = 0;
        $excel->getActiveSheetIndex($sheet_num);
        header('Content-type: text/csv');
        $file_name = $mid . "-Joom-" . date("d-m-Y-His") . ".csv";
        header('Content-Disposition: attachment;filename=' . $file_name . ' ');
        header('Cache-Control: max-age=0');
        foreach ($heard_name as $index => $name)
        {
            $excel->getActiveSheet()->setCellValue(PHPExcelTools::stringFromColumnIndex($index) . '1', $name);

        }

        foreach ($ret as $row_num => $row)
        {
            if(!\is_array($row)){
                return;
            }

            $cell_num = 0;
            foreach ($row as $index => $name)
            {
                $excel->getActiveSheet()->setCellValue(PHPExcelTools::stringFromColumnIndex($cell_num) .($row_num + 2), $name);
                ++$cell_num;
            }

        }
//        $writer =  new \PHPExcel_Writer_Excel5($excel);
        $writer = \PHPExcel_IOFactory::createWriter($excel,'CSV');
        $writer->setDelimiter(',');
        $writer->save('php://output');

    }


    /**
     * @brief export lots csv
     * @param @lots_mid string
     */
    public function actionExportLots($lots_mid)
    {
        $lots_mid = explode(',',$lots_mid);
        $db = Yii::$app->db;
        $sql = "select odm.parentId,odm.proName,odm.description,odm.tags,
                odm.childId,odm.color,odm.proSize,odm.quantity,odm.price,odm.msrPrice,
                odm.shipping,odm.shippingWeight,odm.shippingTime,odm.MainImage,odm.varMainImage,
                extra_image1,extra_image2,extra_image3,
                extra_image4,extra_image5,extra_image6,extra_image7,
                extra_image8,extra_image9,extra_image10,'' as extra_image0,
                CASE
                WHEN isnull(isLiquid, 0) = 1 THEN
                    'liquid'
                ELSE
                    CASE
                WHEN isnull(IsPowder, 0) = 1 THEN
                    'powder'
                ELSE
                    CASE
                WHEN isnull(isMagnetism, 0) = 1 THEN
                    'withBattery'
                ELSE
                    CASE
                WHEN isnull(IsCharged, 0) = 1 THEN
                    'withBattery'
                ELSE
                    'notDangerous'
                END
                END
                END
                End  AS 'Dangerous Kind'
                from oa_data_mine_detail as odm left join oa_data_mine as om
                on om.id=odm.mid
                where mid=:mid";


        $heard_name = [
            'Parent Unique ID',
            '*Product Name',
            'Description',
            '*Tags',
            '*Unique ID',
            'Color',
            'Size',
            '*Quantity',
            '*Price',
            '*MSRP',
            '*Shipping',
            'Shipping weight',
            'Shipping Time(enter without " ", just the estimated days )',
            '*Product Main Image URL',
            'Variant Main Image URL',
            'Extra Image URL',
            'Extra Image URL 1',
            'Extra Image URL 2',
            'Extra Image URL 3',
            'Extra Image URL 4',
            'Extra Image URL 5',
            'Extra Image URL 6',
            'Extra Image URL 7',
            'Extra Image URL 8',
            'Extra Image URL 9',
            'Extra Image URL 10',
            'Dangerous Kind'
        ];

        $excel = new \PHPExcel();
        $sheet_num = 0;
        $excel->getActiveSheetIndex($sheet_num);
        header('Content-type: text/csv');
        $file_name = "Joom-" . date("d-m-Y-His") . ".csv";
        header('Content-Disposition: attachment;filename=' . $file_name . ' ');
        header('Cache-Control: max-age=0');
        foreach ($heard_name as $index => $name)
        {
            $excel->getActiveSheet()->setCellValue(PHPExcelTools::stringFromColumnIndex($index) . '1', $name);

        }

        $row_count = 2;
        foreach ($lots_mid as $mid){
            $query = $db->createCommand($sql ,[':mid' => $mid]);
            $ret = $query->queryAll();
            foreach ($ret as $row_num => $row)
            {
                if(!\is_array($row)){
                    return;
                }

                $cell_num = 0;
                foreach ($row as $index => $name)
                {
                    $excel->getActiveSheet()->setCellValue(PHPExcelTools::stringFromColumnIndex($cell_num) .($row_num + $row_count), $name);
                    ++$cell_num;
                }

            }
            $row_count += \count($ret);

        }

        $writer = \PHPExcel_IOFactory::createWriter($excel,'CSV');
        $writer->setDelimiter(',');
        $writer->save('php://output');

    }


    /*
     * @brief complete lots
     * @return string
     */
    public function actionCompleteLots()
    {
        $post = Yii::$app->request->post();
        $lots_mid = $post['lots_mid'];
        $trans = Yii::$app->db->beginTransaction();
        try{
            foreach ($lots_mid as $mid) {
                $mine = OaDataMine::findOne(['id' => $mid]);
                $mine->setAttribute('detailStatus','已完善');
                if(!$mine->update()){
                    throw  new \Exception('fail to update！');
                }
            }
            $trans->commit();
            $msg = '标记成功！';
        }
        catch (\Exception $why){
            $trans->rollBack();
            $msg = '标记失败';
        }
        return $msg;
    }


    /**
     * @brief set price
     */
    public  function  actionSetPrice()
    {
        $post = Yii::$app->request->post();
        $op = $post['op'];
        $lots_mid = $post['lots_mid'];
        $price_replace = $post['price_replace'];
        $trans = Yii::$app->db->beginTransaction();
        try{
            foreach ($lots_mid as $mid) {
                $detail = OaDataMineDetail::findAll(['mid' => $mid]);
                foreach ($detail as $row) {
                    $current_price = $row->price;
                    $new_price = round($this->calculate($op, $current_price, $price_replace),2);
                    $row->setAttribute('price', $new_price);
                    if(!$row->update()){
                        throw new Exception('fail to update!');
                    }
                }
            }
            $trans->commit();
            $msg = '价格设置成功！';

        }
        catch (\Exception $why){
            $trans->rollBack();
            $msg = '价格设置失败';
        }
        return $msg;

    }


    /**
     * @brief set cat
     */
    public  function  actionSetCat()
    {
        $post = Yii::$app->request->post();
        $cat = $post['cat'];
        $lots_mid = $post['lots_mid'];
        $sub_cat = $post['sub_cat'];
        $trans = Yii::$app->db->beginTransaction();
        try{
            foreach ($lots_mid as $mid) {
                $detail = OaDataMine::findAll(['id' => $mid]);
                foreach ($detail as $row) {
                    $row->setAttribute('cat', $cat);
                    $row->setAttribute('subCat', $sub_cat);
                    if(!$row->update()){
                        throw new Exception('fail to update!');
                    }
                }
            }
            $trans->commit();
            $msg = '类目设置成功！';

        }
        catch (\Exception $why){
            $trans->rollBack();
            $msg = '类目设置失败！';
        }
        return $msg;

    }



    /**
     * @brief save basic data
     */
    public function actionSaveBasic($mid,$flag='')
    {
        $post = Yii::$app->request->post();
        $images = $post['images'];
        $images_num = \count($images);
        for($i=$images_num +1;$i<=10;$i++){
            $images['extra_image'.$i] = '';
        }
        $form = $post['form'];
        $detail_models = OaDataMineDetail::findAll(['mid'=>$mid]);
        $trans = Yii::$app->db->beginTransaction();
        try {
            $cat = $form['cat'];
            $sub_cat = $form['subCat'];
            $spAttribute = $form['spAttribute'];
            $mine = OaDataMine::findOne(['id'=>$mid]);
            $attributes = [
                '' => '', 'isLiquid' => '液体商品', 'isCharged' => '带电商品',
                'isMagnetism' => '带磁商品', 'isPowder' => '粉末商品'];

            $mine->spAttribute = $attributes[$spAttribute];
            if(!empty($spAttribute)){
                $mine->$spAttribute = 1;
            }
            else {
                $mine->isLiquid = 0;
                $mine->isPowder = 0;
                $mine->isMagnetism = 0;
                $mine->isCharged = 0;
            }
            $mine->cat = $cat;
            $mine->subCat = $sub_cat;

            if(!$mine->save()){
                throw new \Exception('保存失败！');
            }

            if($flag === 'complete'){
                $mine->detailStatus = '已完善';
                if(!$mine->save()){
                    throw new \Exception('标记失败！');
                }
            }
            foreach($detail_models as $detail){
                $detail->setAttributes($form);
                $detail->setAttributes($images);
                if(!$detail->save()){
                   throw new \Exception("保存失败！");
                }
            }
            $trans->commit();
            $msg = '保存成功';
        }
        catch (\Exception $why){
            $trans->rollBack();
            $msg = '保存失败';
        }
        return $msg;
    }


    /**
     * @brief delete detail
     * @param @id int
     */
    public function actionDeleteDetail($id=null)
    {
        $id = $id?$id:Yii::$app->request->post()['id'];
        OaDataMineDetail::deleteAll(['id'=>$id]);
    }


    /**
     * @brief save detail
     * @param @id int
     * @throws
     * @return mixed
     */
    public function actionSaveDetail($mid)
    {
        $post = Yii::$app->request->post();
        $details = $post['OaDataMineDetail'];
        $trans = Yii::$app->db->beginTransaction();
        try{
            $basic = OaDataMineDetail::findOne(['mid'=>$mid]);
            foreach($details as $key => $row){
                if(strpos($key,'New-')!==false){
                    //create
                    $model = new OaDataMineDetail();
                    $model->attributes = $basic->attributes;
                    $model->setAttributes($row);
                    if(!$model->save()){
                        throw new \Exception('fail to create');
                    }

                }
                else{
                    //update
                    $model = OaDataMineDetail::findOne(['id' => $key]);
                    $model->setAttributes($row);
                    if(!$model->update()){
                        throw new \Exception('fail to update');
                    }

                }
            }
            $trans->commit();
            $msg = '保存成功！';
        }

        catch(\Exception $why){
            $trans->rollBack();
            $msg = '保存失败！';
        }
        return $msg;

        //create or update

    }

    /**
     * @brief send OaDataMine record to goods-info
     * @param  $stock int
     * @return mixed
     * @throws \Exception
     */
    public function actionSend($stock=0)
    {
        $post = Yii::$app->request->post();
        $db = Yii::$app->db;
        $mid = (int)$post['mid'];
        $stock = (int)$stock;

        $user = Yii::$app->user->identity->username;

        $mine = OaDataMine::findOne($mid);
        if($mine->devStatus !=='未开发') {
            return '该状态下产品不能转至开发';
        }

        $send_sql  = 'p_oa_joomToCheck @mid=:mid,@dev=:dev,@stock=:stock';
        $query = $db->createCommand($send_sql,[':mid'=>$mid,':dev'=>$user,':stock'=>$stock]);
        if($query->execute($send_sql)){
            return '已成功转至开发';
        }
        return '转至失败！';

    }

    /**
     * Finds the OaDataMine model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OaDataMine the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OaDataMine::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @brief generate goods code
     * @param string $max_code
     * @return string
     */
    private function generateCode($max_code)
    {
        $number = (int)substr($max_code,9,\strlen($max_code)-1) + 1;
        $base = '0000';
        if(\strlen($number) === \strlen($base)){
            return 'A'.date('Ymd').$number;
        }
        $code = substr($base,0,\strlen($base) - \strlen($number)).$number;
        return 'A'.date('Ymd').$code;
    }

    /**
     * @brief get sub-cat
     * @param $cat string
     * @return array
     */
    public function actionSubCat($cat)
    {
        if(empty($cat)){
            return json_encode([]);
        }
        $db = yii::$app->db;
        $cache = yii::$app->local_cache;
        $cat_sql = 'select categoryName from b_goodsCats where CategoryParentName=:cat';
        $ret = $cache->get($cat);
        if($ret !== false){
            $result = $ret;
        }
        else{
            $result = $db->createCommand($cat_sql,['cat'=>$cat])->queryAll();
            $cache->set($cat,$result,2592000);
        }
        return  json_encode(\array_map(function ($ele) { return $ele['categoryName'];},$result));
    }


    /**
     * @brief calculate
     * @param operator string
     * @param price float
     * @param price_replace float
     *
     * @return float
     */
    private function calculate($operator, $price, $price_replace){
        if(!is_numeric($price) || !is_numeric($price_replace)){
            return '';
        }

        $price = (float)$price;
        $price_replace = (float)$price_replace;

        if($operator === '='){
           $ret = $price_replace;
        }

        if($operator === '+'){
            $ret = $price + $price_replace;

        }

        if($operator === '-'){
            $ret = $price - $price_replace;

        }

        if($operator === '*'){
            $ret = $price * $price_replace;

        }
        if($operator === '/'){
            $ret = $price / $price_replace;

        }
        return $ret;
    }


}
