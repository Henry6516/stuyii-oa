<?php

namespace backend\controllers;

use backend\models\OaSupplierOrderDetail;
use backend\models\OaSupplierOrderPaymentDetail;
use backend\models\OaSupplierOrderPaymentSearch;
use Yii;
use backend\models\OaSupplierOrder;
use backend\models\OaSupplierOrderSearch;
use backend\models\UploadFile;
use yii\data\ArrayDataProvider;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\unitools\PHPExcelTools;
use yii\data\ActiveDataProvider;
use backend\services\OaSupplierOrderServicel;
use yii\web\UploadedFile;

/**
 * OaSupplierOrderController implements the CRUD actions for OaSupplierOrder model.
 */
class OaSupplierOrderController extends Controller
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
     * Lists all OaSupplierOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OaSupplierOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $file = new UploadFile();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'file' => $file
        ]);
    }

    /**
     * Displays a single OaSupplierOrder model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $orderDetail = new ActiveDataProvider([
            'query' => OaSupplierOrderDetail::find()->joinWith('oa_SupplierOrder')->where(['orderId' => $id])->select(
                'oa_SupplierOrderDetail.*,oa_SupplierOrder.billNumber'
            ),
            'pagination' => ['pageSize' => 200]
        ]);
        $sort = $orderDetail->sort;
        $sort->attributes['billNumber'] = ['asc'=>['billNumber'=>SORT_ASC],'desc'=>['billNumber'=>SORT_DESC]];
        $orderDetail->sort= $sort;
        return $this->render('view', [
            'dataProvider' => $orderDetail
        ]);
    }

    /**
     * Creates a new OaSupplierOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OaSupplierOrder();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OaSupplierOrder model.
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

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @brief save order detail
     * @return mixed
     * @throws
     */
    public function actionSaveOrderDetail()
    {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            return '错误请求！';
        }
        $post = $request->post();
        $details = $post['OaSupplierOrderDetail'] ?? [];
        $trans = Yii::$app->db->beginTransaction();
        try {
            foreach ($details as $detailId => $row) {
                $detail = OaSupplierOrderDetail::findOne(['id' => $detailId]);
                if(!empty($detail)) {
                    $detail->setAttributes($row);
                    if (!$detail->save()) {
                        throw new \Exception('fail to save order details');
                    }
                }
            }
            $msg = '保存成功！';
            $trans->commit();
        }
        catch (\Exception $why)
        {
                $trans->rollBack();
                $msg = '保存失败！';
            }
            return $msg;
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * 获取普元订单列表
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionQuery()
    {
        $request = Yii::$app->request->get();
        if ($request) {
            $list = OaSupplierOrder::getPyOrderData($request);
            $dataProvider = new ArrayDataProvider([
                'allModels' => $list,
                'pagination' => [
                    'pageSize' => 10,
                ],
                'sort' => [
                    'attributes' => ['BillNumber', 'CHECKfLAG', 'SupplierName', 'MakeDate', 'Recorder', 'DelivDate', 'OrderAmount', 'OrderMoney'],
                ],
            ]);
            //设置默认显示的订单明细
            $page = isset($request['page']) ? $request['page'] : 1;
            $pageSize = isset($request['pre-page']) ? $request['pre-page'] : 10;
            if ($list && isset($list[$pageSize * ($page - 1)])) {
                $detailList = OaSupplierOrder::getPyOrderDetail($list[$pageSize * ($page - 1)]['nid']);
            } else {
                $detailList = [];
            }
            //var_dump($detailList);exit;
            return $this->render('query', [
                'search' => $request,
                'dataProvider' => $dataProvider,
                'detailList' => $detailList,
            ]);

        } else {
            return $this->render('query');
        }
    }

    /**
     * @param $id
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionQueryDetail($id)
    {
        //$id = Yii::$app->request->post('id',0);
        $detailList = OaSupplierOrder::getPyOrderDetail($id);
        return $this->renderAjax('queryDetail', [
            'detailList' => $detailList,
        ]);
    }

    /**
     * 采购单明细
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionQueryOrder()
    {
        $ids = Yii::$app->request->post()['ids'];
        $trans = Yii::$app->db->beginTransaction();
        try {
            foreach (json_decode($ids) as $id){
                OaSupplierOrder::syncPyOrders($id);
            }
            $trans->commit();
            $res = '订单同步成功！';
        } catch (\Exception $e){
            $trans->rollBack();
            //$res = $e->getMessage();
            $res = $e->getMessage();
        }
        return $res;
    }

    /**
     * 手动同步普源数据到产品中心
     * @param $id
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionSync($id=[])
    {
        $request = Yii::$app->request;
        if($request->isGet) {
            $id = [$id];
        }
        if($request->isPost) {
            $id = $request->post()['id'];
        }
        $db = Yii::$app->db;
        $trans = $db->beginTransaction();
        try {
           foreach ($id as $key) {
               $sql = "p_oa_SupplierOrderSync $key";
               $res = $db->createCommand($sql)->execute();
               if(!$res) {
                   throw new \Exception('同步失败！');
               }
           }
           $trans->commit();
           $msg = '同步成功！';
        }
        catch (\Exception $why) {
            $trans->rollBack();
            $msg = '同步失败！';
        }
        return $msg;
    }

    /**
     * @brief 审核订单
     * @param $id
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionCheck($id=[]) :string
    {
        $request = Yii::$app->request;
        if($request->isGet) {
            $id = [$id];
        }
        if($request->isPost) {
            $id = $request->post()['id'];
        }
        $db = Yii::$app->db;
        $trans = $db->beginTransaction();
        try {
            foreach ($id as $key) {
                $order = OaSupplierOrder::findOne(['id'=>$key]);
                $billNumber = $order->billNumber;
                $order->billStatus = '已审核';
                $sql = 'update CG_StockOrderM  set CheckFlag=1 where BillNumber=:billNumber';
                $res = $db->createCommand($sql,[':billNumber'=>$billNumber])->execute();
                if(!$res || !$order->save()) {
                    throw new \Exception('审核失败！');
                }
            }
            $trans->commit();
            $msg = '审核成功！';
        }
        catch (\Exception $why) {
            $trans->rollBack();
            $msg = '审核失败！';
        }
        return $msg;
    }

    /**
     * 请求付款
     * @param $id
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionPay($id)
    {
        if(!Yii::$app->request->isPost) {
            return '请求错误！';
        }
        $post = Yii::$app->request->post();
        $paymentAmt = (float)trim($post['number']);
        $order = OaSupplierOrder::findOne(['id'=>$id]);
        $payment = new OaSupplierOrderPaymentDetail();
        //$payment->send($id);
        $db = Yii::$app->db;
        $trans = $db->beginTransaction();
        try {
            //保存订单付款状态
            $order->paymentStatus = '请求付款中';
            //保存付款明细
            $payment->billNumber = $order->billNumber;
            $payment->requestAmt = $paymentAmt;
            $payment->requestTime = date('Y-m-d H:i:s');
            $payment->paymentStatus = '未付款';

            if(!($order->save() && $payment->save())) {
                throw new \Exception('fail to save data!');
            }
            $trans->commit();
            //发送邮件给财务
            $payment->send($id);
            $msg = '请求付款成功！';
        } catch (\Exception $why) {
            $trans->rollBack();
            $msg = '请求付款失败！';
        }
        return $msg;
    }
    /**
     * 付款明细
     * @param $id
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionPayment($id)
    {
        $paymentDetail = new ActiveDataProvider([
            'query' => OaSupplierOrderPaymentDetail::find()
                ->joinWith('oa_SupplierOrder')
                ->where(['oa_SupplierOrder.id' => $id])
                ->select('oa_SupplierOrderPaymentDetail.*'),
            'pagination' => ['pageSize' => 200]
        ]);
        $sort = $paymentDetail->sort;
        $sort->attributes['billNumber'] = ['asc'=>['billNumber'=>SORT_ASC],'desc'=>['billNumber'=>SORT_DESC]];
        $paymentDetail->sort= $sort;
        $file = new UploadFile();
        return $this->render('payment', [
            'dataProvider' => $paymentDetail,
            'file' => $file
        ]);
    }

    /**
     * 付款明细
     * @return string
     */
    public function actionPaymentList()
    {
        $searchModel = new OaSupplierOrderPaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('paymentList', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 财务上传付款凭证（图片）
     * @return string
     */
    public function actionUpload()
    {
        $file  = new UploadFile();
        $file->excelFile = UploadedFile::getInstance($file,'excelFile');
        $pathName = $file->uploadImg();
        return json_encode([
            'code' => $pathName ? 200 : 400,
            'msg' => $pathName ? '上传成功' : '上传失败',
            'url' => $pathName,
        ]);
    }
    /**
     * 财务保存付款结果
     * @return string
     */
    public function actionSavePayment()
    {
        $request = Yii::$app->request;
        if(!$request->isPost) {
            return '';
        }
        $post = $request->post()['OaSupplierOrderPaymentDetail'];
        $db = Yii::$app->db;
        $trans = $db->beginTransaction();
        try{
            foreach ($post as $value){
                $model = OaSupplierOrderPaymentDetail::findOne($value['id']);
                $model->attributes = $value;
                $model->paymentStatus = '已支付';
                $model->paymentTime = date('Y-m-d H:i:s');
                if(!$model->save()){
                    throw new \Exception('fail to save data!');
                }
            }
            $trans->commit();
            $msg = '保存成功！';
        }catch (\Exception $e){
            $trans->rollBack();
            $msg = '保存失败！';
        }
        return $msg;
    }

    /**
     * @brief 发货
     * @param $id int orderId
     * @return mixed
     * @throws
     */
    public function actionDelivery($id)
    {
        if(!Yii::$app->request->isPost) {
            return '请求错误！';
        }
        $post = Yii::$app->request->post();
        $expressNumber = $post['number'];
        $numbers = explode("\n",trim($expressNumber));
        $numbers = implode(',',$numbers);
        $sql = "update oa_supplierOrder set expressNumber='$numbers' where id=$id";
        $db = Yii::$app->db;
        $res = $db->createCommand($sql)->execute()  ;
        if(!$res) {
            return '发货失败！';
        }
        return '发货成功！';
    }

    /**
     * @brief 导入物流单号到普源
     * @param $id
     * @return mixed
     * @throws
     */
    public function actionInputExpress($id=[])
    {
        $request = Yii::$app->request;
        if ($request->isGet) {
            $ids = [$id];
        }
        if ($request->isPost) {
            $ids = $request->post()['id'];
        }
        $db = Yii::$app->db;
        $trans = $db->beginTransaction();
        try {
            foreach($ids as $key) {
                $order = OaSupplierOrder::findOne($key);
                $billNumber = $order->billNumber;
                $expressNumber = $order->expressNumber;
                $sql = "update cg_stockOrderM  set logisticOrderNo='$expressNumber' where BillNumber='$billNumber'";
                $res = $db->createCommand($sql)->execute();
                if (!$res) {
                    throw new \Exception('导入失败！');
                }
            }
            $trans->commit();
            $msg = '导入成功！';
        }
        catch (\Exception $why) {
            $trans->rollBack();
            $msg = '导入失败！';
        }
        return $msg;
    }


    /**
     * @brief 导出采购单明细
     * @param $id string
     * @throws
     */
    public function actionExportDetail($id='')
    {
        $ids = explode(',',$id);
        $db = Yii::$app->db;
        $fileName = $sheetName = '采购单明细';
        //表头
        $headers = [
            '采购单号',
            'SKU',
            '供应商SKU',
            '产品名称',
            '款式1',
            '款式2',
            '款式3',
            '采购数量',
            '采购价',
            '发货数量',
        ];
        $outs = [];
        foreach ($ids as $key) {
            $sql = "p_oa_exportOrderDetail $key";
            $ret = $db->createCommand($sql)->queryAll();
            foreach ($ret as $row) {
                $outs[] = $row;
            }
        }
        PHPExcelTools::exportExcel($fileName,$sheetName,$headers,$outs);
    }

    /**
     * @brief 发货单模板
     */
    public function actionDeliveryTemplate()
    {
       $fileName = '发货单模板';
       $sheetName = '发货单';
       $headers = [
           '采购单号',
           '供应商SKU',
           '发货数量',
           '物流单号'
       ];
       $data = [
            [
                'CGD-2018-08-03-0204',
                'A0001-X',
                6,
                'XXXXX',
            ]
       ];
       PHPExcelTools::exportExcel($fileName,$sheetName,$headers,$data);
    }


    /**
     * @brief 接受发货
     * @return mixed
     */

    public function actionInputDeliveryOrder()
    {
        $request = Yii::$app->request;
        if(!$request->isPost) {
            return '';
        }
        $file  = new UploadFile();
        $file->excelFile = UploadedFile::getInstance($file,'excelFile');
        $pathName = $file->upload();
        if ($pathName) {
            $keys = [
                'billNumber',
                'supplierGoodsSku',
                'deliveryAmt',
                'expressNumber'
            ];
            $rows = PHPExcelTools::readExcel($pathName,$keys);
            $ret = $this->updateOrder($rows);
            return $ret?'上传成功！':'上传失败！';
        }
        return '上传失败！';
    }


    /**
     * Finds the OaSupplierOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OaSupplierOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OaSupplierOrder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @brief update supplier order and supplier order detail
     * @param $rows array
     * @return boolean
     * @throws
     */
    private function updateOrder($rows)
    {
        $db = Yii::$app->db;
        $trans = $db->beginTransaction();
        try {
            foreach ($rows as $row) {
                $order = OaSupplierOrder::findOne(['billNumber'=>$row['billNumber']]);
                $orderId = $order->id;
                $oldExpressNumber = $order->expressNumber;
                $orderDeatail = OaSupplierOrderDetail::findOne(['orderId'=>$orderId,'supplierGoodsSku'=>$row['supplierGoodsSku']]);
                $orderDeatail->deliveryAmt = $row['deliveryAmt'];
                $orderDeatail->deliveryTime = date('Y-m-d H:i:s');
                if(empty($oldExpressNumber)) {
                    $order->expressNumber = $row['expressNumber'];
                } else {
                    if($oldExpressNumber !== $row['expressNumber']) {
                        $order->expressNumber = $oldExpressNumber.','.$row['expressNumber'];
                    }
                }
                if(!($orderDeatail->save() && $order->save())) {
                    throw new \Exception('fail to save data!');
                }
            }
            $trans->commit();
            return true;
        }
        catch (\Exception $why) {
            $trans->rollBack();
            return false;
        }
    }
}
