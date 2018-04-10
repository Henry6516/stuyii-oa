<?php

namespace backend\controllers;

use backend\unitools\PHPExcelTools;
use Yii;
use app\models\OaDataMine;
use app\models\OaDataMineSearch;
use app\models\OaDataMineDetail;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\IntegrityException;
use yii\web\Response;
use yii\data\ActiveDataProvider;
/**
 * OaDataMineController implements the CRUD actions for OaDataMine model.
 */
class OaDataMineController extends Controller
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
        $mine = OaDataMineDetail::findOne(['mid' => $id]);
        return $this->render('show-basic', [
            'mine' => $mine,
            'mid' => $id,
        ]);
    }


    /**
     * @brief detail for modal
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
            $max_code = $db->createCommand($max_code_sql)->queryOne()['goodsCode']?? date('Ydm').'00000';
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
        $sql = "select parentId,proName,description,tags,
                childId,color,proSize,quantity,price,msrPrice,
                shipping,shippingWeight,shippingTime,MainImage,varMainImage,
                extra_image0,extra_image1,extra_image2,extra_image3,
                extra_image4,extra_image5,extra_image6,extra_image7,
                extra_image8,extra_image9,extra_image10 from oa_data_mine_detail
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
        ];

        $excel = new \PHPExcel();
        $sheet_num = 0;
        $excel->getActiveSheetIndex($sheet_num);
        header('Content-Type: application/vnd.ms-excel');
        $file_name = $mid . "-Joom-" . date("d-m-Y-His") . ".xls";
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
        $writer =  new \PHPExcel_Writer_Excel5($excel);
        $writer->save('php://output');

    }


    /**
     * @brief save basic data
     */
    public function actionSaveBasic($mid,$flag='')
    {
        $post = Yii::$app->request->post();
        $images = $post['images'];
        $form = $post['form'];
        $detail_models = OaDataMineDetail::findAll(['mid'=>$mid]);
        $trans = Yii::$app->db->beginTransaction();
        try {
            if($flag === 'complete'){
                $mine = OaDataMine::findOne(['id'=>$mid]);
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
        $number = (int)substr($max_code,8,\strlen($max_code)-1) + 1;
        $base = '00000';
        $code = substr($base,0,\strlen($base) - \strlen($number)).$number;
        return date('Ymd').$code;
    }
}
