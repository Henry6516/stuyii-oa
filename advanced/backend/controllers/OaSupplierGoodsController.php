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
    public function behaviors() :array
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
     * @throws
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
     * @throws
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
     * @throws
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
     * @throws
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
            $msg = '删除成功！';
        }
        catch (\Exception $why) {
            $msg = '删除失败！';
        }

        return $msg;
    }

    /**
     * Deletes oaSupplierGoodsSku row by id
     * @param integer $id
     * @return mixed
     * @throws
     */
    public function actionDeleteSku($id)
    {
        try {
            $sku = OaSupplierGoodsSku::findOne(['id'=>$id]);
            if(!empty($sku)) {
                $sku->delete();
            }
        }
        catch (\Exception $why) {
           return '删除失败！';
        }
        return '删除成功！';
    }

    /**
     * Save sku detail
     * @return mixed
     */
    public function actionSaveSku()
    {
        $post = Yii::$app->request->post();
        $skuDetails = \is_array($post['OaSupplierGoodsSkuSearch'])?$post['OaSupplierGoodsSkuSearch']:[];
        $trans = Yii::$app->db->beginTransaction();
        try {
            foreach ($skuDetails as $id=>$row) {
                $sku = OaSupplierGoodsSku::findOne(['id'=>$id]);
                if(!empty($sku)) {
                    $sku->setAttributes($row);
                    if(!$sku->save()) {
                        throw new Exception('保存失败！');
                    }
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
     * @param string $supplier
     * @return mixed
     */
    public function actionGetPurchaser($supplier) {
        return OaSupplier::find()->select('purchase')->where(['supplierName'=>$supplier])->one()->purchase;
    }

    /**
     * @brief get goods name based on goods code
     * @param string $goodsCode
     * @return mixed
     * @throws
     */
    public function actionGetGoodsName($goodsCode) {
        $sql = 'select goodsName from B_Goods where goodsCode=:goodsCode';
        $db = Yii::$app->db;
        $ret = $db->createCommand($sql,[':goodsCode'=>$goodsCode])->queryOne();
        return $ret['goodsName'];
    }

    /**
     * Finds the OaSupplierGoods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OaSupplierGoods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) :OaSupplierGoods
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
    private function getSuppliers () :array
    {
        $supplier = OaSupplier::find()->select('supplierName')->distinct()->asArray()->all();
        return ArrayHelper::map($supplier,'supplierName','supplierName');
    }
}
