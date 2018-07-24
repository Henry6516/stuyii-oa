<?php

namespace backend\controllers;

use backend\models\User;
use Yii;
use backend\models\OaSupplier;
use backend\models\OaSupplierSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * SupplierController implements the CRUD actions for OaSupplier model.
 */
class SupplierController extends Controller
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
     * Lists all OaSupplier models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OaSupplierSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OaSupplier model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new OaSupplier model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OaSupplier();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $supplierId = Yii::$app->db->createCommand("SELECT nid FROM B_supplier WHERE supplierName LIKE '%".$model->supplierName."%'")->queryOne();
            $model->supplierId = $supplierId['nid'];
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        //获取供应商列表
        $sql = "SELECT nid,supplierName FROM B_supplier WHERE used=0 ORDER BY supplierName";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $data = ArrayHelper::map($res,'supplierName','supplierName');

        //判断当前用户的供应商数量
        $user = Yii::$app->user->identity->username;
        $userModel = User::findOne(['username' => $user]);
        $num = OaSupplier::find()->andWhere(['purchase' => $user])->count();
        if($userModel['department']=='供应链管理' && $userModel['maxSupplierNum']<=$num){
            $res = 'no';
        }else{
            $res = 'yes';
        }

        return $this->render('create', [
            'model' => $model,
            'data' => $data,
            'status' => $res,
        ]);
    }

    /**
     * Updates an existing OaSupplier model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $supplierId = Yii::$app->db->createCommand("SELECT nid FROM B_supplier WHERE supplierName LIKE '%".$model->supplierName."%'")->queryOne();
            $model->supplierId = $supplierId['nid'];
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        //获取供应商列表
        $sql = "SELECT nid,supplierName FROM B_supplier WHERE used=0 ORDER BY supplierName";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $data = ArrayHelper::map($res,'supplierName','supplierName');
        return $this->render('update', [
            'model' => $model,
            'data' => $data,
        ]);
    }

    /**
     * Deletes an existing OaSupplier model.
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
     * Finds the OaSupplier model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OaSupplier the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OaSupplier::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /**
     * 查询供应商名称
     * @return array
     */
    public function actionSearch(){
        $q = Yii::$app->request->get('q');
        Yii::$app->response->format = Response::FORMAT_JSON;//响应数据格式为json
        $out = ['results' => ['supplierName' => '']];
        if (!$q) {
            return $out;
        }

        $sql = "SELECT supplierName FROM B_supplier WHERE used=0 AND supplierName LIKE '%{$q}%' ORDER BY supplierName";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        print_r($res);exit;
        $data = ArrayHelper::map($res,'supplierName','supplierName');

        return $data;
    }


}
