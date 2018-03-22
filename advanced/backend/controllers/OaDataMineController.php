<?php

namespace backend\controllers;

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
        return $this->render('data-detail', [
            'mid' => $id,
        ]);
    }

    /**
     * @brief data detail in Json
     * @return mixed
     */
    public function actionDataDetail($id='')
    {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $detail = OaDataMineDetail::findAll(['mid' => $id]);
//        $ret = json_decode(CJSON::encode($detail),TRUE);
        return $detail;


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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
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
