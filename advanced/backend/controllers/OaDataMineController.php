<?php

namespace backend\controllers;

use backend\unitools\PHPExcelTools;
use Yii;
use app\models\OaDataMine;
use app\models\OaDataMineSearch;
use app\models\OaDataMineDetail;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\IntegrityException;
use yii\web\Response;

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
                    'delete' => ['POST'],
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

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
     * Deletes an existing OaDataMine model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * create crawl job
     * @return mixed
     */
    public function actionCreateJob()
    {

        $job_model = new OaDataMine();
        $request = Yii::$app->request->post();
        $pro_id = trim($request['proId']);
        $platform = $request['platform'];
        $creator = Yii::$app->user->identity->username;
        $current_time = date('Y-m-d H:i:s');
        $job_model->proId = $pro_id;
        $job_model->platForm = $platform;
        $job_model->creator = $creator;
        $job_model->createTime = $current_time;
        $job_model->updateTime = $current_time;
        $job_model->progress = '待采集';
        try
        {
            if($job_model->save()){
                $job_id = $job_model->id;
                $redis = Yii::$app->redis;
                $redis->lpush('job_list',$job_id.','.$pro_id);
                $msg = "任务已添加到队列！";
            }
            else {
                $msg = "任务添加失败，请重新添加";
            }

        }
        catch(IntegrityException $why){
            $msg = "该商品已采集过，不可重复采集！";
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
     * @brief save data
     */
    public function actionSave($mid)
    {
        $post = Yii::$app->request->post();
        $table_data = json_decode($post['tableData']);
        $form_data = $post['formData'];

        //update,create or delete oa-data-mine-detail
        $trans = Yii::$app->db->beginTransaction();

        try {
            foreach($table_data as $row)
            {
                $row = json_decode(json_encode($row), true);

                // add basic info to detail
                $row['proName'] = $form_data['proName'];
                $row['tags'] = $form_data['tags'];
                $row['parentId'] = $form_data['parentId'];


                if(empty($row['id'])){
                    //create new one
                    $new_detail = new OaDataMineDetail();
                    unset($row['id']);
                    $row['mid'] = $mid;
                    $new_detail->setAttributes($row,false);
                    if(!$new_detail->save(false)){
                        throw new \Exception("更新失败");
                    }
                }
                else {
                    // update
                    $detail_model = OaDataMineDetail::findOne(['id'=>$row['id']]);
                    $detail_model->setAttributes($row,false);
                    if(!$detail_model->save(false)){
                        throw new \Exception("更新失败");
                    }

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
    public function actionDeleteDetail($id)
    {
        OaDataMineDetail::deleteAll(['id'=>$id]);
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
}
