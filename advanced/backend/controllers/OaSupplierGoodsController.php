<?php

namespace backend\controllers;

use backend\models\OaSupplierGoodsSku;
use Yii;
use backend\models\OaSupplier;
use backend\models\OaSupplierGoods;
use backend\models\OaSupplierGoodsSearch;
use backend\models\OaSupplierGoodsSkuSearch;
use yii\db\Exception;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * OaSupplierGoodsController implements the CRUD actions for OaSupplierGoods model.
 */
class OaSupplierGoodsController extends Controller
{
    /**
     * {@inheritdoc}
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
     * Lists all OaSupplierGoods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OaSupplierGoodsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays the OaSupplierGoodsSku.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $skuDetail = new ActiveDataProvider([
            'query' => OaSupplierGoodsSkuSearch::find()->where(['supplierGoodsId'=>$id]),
            'pagination' => ['pageSize'=>200]
        ]);

        return $this->render('view', [
            'dataProvider' => $skuDetail,
        ]);
    }

    /**
     * Creates a new OaSupplierGoods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OaSupplierGoods();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //TODO save goods detail
            $goodsCode = $model->goodsCode;
            $supplierGoodsId = $model->id;
            $sql = "P_oa_SkuToSupplierGoodsSku '$goodsCode','$supplierGoodsId' ";
            Yii::$app->db->createCommand($sql)->execute();
            return $this->redirect(['index']);
        }

        $suppliers = $this->getSuppliers();
        return $this->render('create', [
            'model' => $model,
            'suppliers' => $suppliers
        ]);
    }

    /**
     * Updates an existing OaSupplierGoods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        $suppliers = $this->getSuppliers();
        return $this->render('update', [
            'model' => $model,
            'suppliers' => $suppliers
        ]);
    }

    /**
     * Deletes an existing OaSupplierGoods model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws HttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Deletes oaSupplierGoodsSku row by id
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteSku($id)
    {
        OaSupplierGoodsSku::findOne(['id'=>$id])->delete();

        return '删除成功！';
    }

    /**
     * Save sku detail
     * @return mixed
     */
    public function actionSaveSku()
    {
        $post = Yii::$app->request->post();
        $skuDetails = $post['OaSupplierGoodsSkuSearch'];
        $trans = Yii::$app->db->beginTransaction();
        try {
            foreach ($skuDetails as $id=>$row) {
                $sku = OaSupplierGoodsSku::findOne(['id'=>$id]);
                $sku->setAttributes($row);
                if(!$sku->save()) {
                    throw new Exception('保存失败！');
                }
            }
            $trans->commit();
            $msg = '保存成功！';
        }
        catch (Exception $why) {
            $msg = '保存失败！';
        }

        return $msg;
    }
    /**
     * Get purchaser name based on supplier name
     * @param integer $id
     * @return mixed
     */
    public  function actionGetPurchaser($id) {
        return OaSupplier::find()->select('purchase')->where(['id'=>$id])->one()->purchase;
    }

    /**
     * Finds the OaSupplierGoods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OaSupplierGoods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OaSupplierGoods::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*
     * get suppliers
     * @return array
     */
    private function getSuppliers()
    {
        $supplier = OaSupplier::find()->select('id,supplierName')->asArray()->all();
        return ArrayHelper::map($supplier,'id','supplierName');
    }
}
