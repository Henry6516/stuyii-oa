<?php

namespace backend\controllers;

use backend\models\OaTaskSendee;
use backend\models\User;
use Yii;
use backend\models\OaTask;
use backend\models\OaTaskSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskController implements the CRUD actions for OaTask model.
 */
class TaskController extends Controller
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
     * Lists all OaTask models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OaTaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'index');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 未处理列表
     * @return mixed
     */
    public function actionUnfinished()
    {
        $searchModel = new OaTaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,'unfinished');

        return $this->render('unfinished', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 已处理列表
     * @return mixed
     */
    public function actionFinished()
    {
        $searchModel = new OaTaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'finished');

        return $this->render('finished', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    } /**
     * 我发布的任务
     * @return mixed
     */
    public function actionMyTask()
    {
        $searchModel = new OaTaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'ma-task');

        return $this->render('my-task', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OaTask model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        //已处理人员列表
        $completeArr = OaTaskSendee::find()->where(['taskid' => $id, 'status' => '已处理'])->asArray()->all();
        //未处理人员列表
        $unfinishedArr = OaTaskSendee::find()->where(['taskid' => $id, 'status' => ''])->asArray()->all();
        //任务进度
        $schedule = round(count($completeArr)/(count($completeArr)+count($unfinishedArr)) * 100, 2);
        return $this->render('view', [
            'model' => $model,
            'completeName' => $completeArr,
            'unfinishedName' => $unfinishedArr,
            'schedule' => $schedule
        ]);
    }

    /**
     * Creates a new OaTask model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OaTask();
        $post = Yii::$app->request->post();

        if ($model->load($post)) {
            //var_dump($post);exit;
            $transaction  = Yii::$app->db->beginTransaction();
            try {
                $model->attributes = $post['OaTask'];
                $model->userid = Yii::$app->user->identity->getId();
                $model->createdate = date('Y-m-d H:i:s');
                $model->save();

                //保存接收人
                foreach ($post['OaTask']['sendee'] as $value){
                    $sendModel = new OaTaskSendee();
                    $sendModel->userid = $value;
                    $sendModel->taskid = $model->taskid;
                    $sendModel->save();
                }
                //提交
                $transaction->commit();
            } catch (\Exception $e) {
                //回滚
                $transaction->rollBack();
                throw $e;
            }
            return $this->redirect(['my-task']);
        }
        return $this->render('create', [
            'model' => $model,
            'userList' => OaTask::getUserList()
        ]);
    }

    /**
     * Updates an existing OaTask model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            $transaction  = Yii::$app->db->beginTransaction();
            try {
                $model->attributes = $post['OaTask'];
                $model->userid = Yii::$app->user->identity->getId();
                $model->createdate = date('Y-m-d H:i:s');
                $model->save();

                //删除原有接收人
                OaTaskSendee::deleteAll(['taskid' => $id]);
                //保存新的接收人
                foreach ($post['OaTask']['sendee'] as $value){
                    $sendModel = new OaTaskSendee();
                    $sendModel->userid = $value;
                    $sendModel->taskid = $model->taskid;
                    $sendModel->save();
                }
                //提交
                $transaction->commit();
            } catch (\Exception $e) {
                //回滚
                $transaction->rollBack();
                throw $e;
            }
            return $this->redirect(['my-task']);
        }
        //设置执行人初始值
        $sendeeList = OaTaskSendee::findAll(['taskid' => $id]);
        $sendeeList = ArrayHelper::getColumn($sendeeList, 'userid');
        $model->sendee = $sendeeList;

        return $this->render('update', [
            'model' => $model,
            'userList' => OaTask::getUserList(),
        ]);
    }

    /**
     * Deletes an existing OaTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $transaction  = Yii::$app->db->beginTransaction();
        try {
            //删除任务接收人
            OaTaskSendee::deleteAll(['taskid' => $id]);
            //删除任务
            $this->findModel($id)->delete();
            //提交
            $transaction->commit();
        } catch (\Exception $e) {
            //回滚
            $transaction->rollBack();
            throw $e;
        }
        return $this->redirect(['my-task']);
    }

    /**
     * Finds the OaTask model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OaTask the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OaTask::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     *单个处理任务
     */
    public function actionComplete($id){
        $userid = Yii::$app->user->identity->getId();
        $info = OaTaskSendee::findOne(['taskid' => $id, 'userid' => $userid]);

        $info->status = '已处理';
        $info->updatetime = date('Y-m-d H:i:s');
        $ret = $info->save(false);
        return $ret ? "任务处理成功!" : '任务处理失败!';
    }
    /**
     * 批量处理任务
     */
    public function actionCompleteLots(){
        $userid = Yii::$app->user->identity->getId();
        $ids = $_GET['ids'];
        try {
            foreach ($ids as $id) {
                $model = OaTaskSendee::findOne(['taskid' => $id, 'userid' => $userid]);
                $model->status = '已处理';
                $model->updatetime = date('Y-m-d H:i:s', time());
                $model->save(false);
            }
            echo "批量标记完成";
        } catch (\Exception $e) {
            echo "批量任务处理失败";
        }
    }


}