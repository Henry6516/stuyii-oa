<?php

namespace backend\controllers;

use backend\models\OaSupplierOrderDetail;
use Yii;
use backend\models\OaSupplierOrder;
use backend\models\OaSupplierOrderSearch;
use backend\models\UploadFile;
use yii\data\ArrayDataProvider;
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
            'query' => OaSupplierOrderDetail::find()->where(['orderId' => $id]),
            'pagination' => ['pageSize' => 200]
        ]);
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
     * Deletes an existing OaSupplierOrder model.
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
     * query order
     * @return mixed
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
            if ($list && isset($list[$pageSize * ($page - 1) + 1])) {
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
     *  采购单明细
     * @return mixed
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
            $res = $e->getMessage();
        }
        return $res;
    }

    /*
     * @brief 手动同步普源数据到产品中心
     * @param $id int orderId
     * @return mixed
     */
    public function actionSync($id)
    {
        $sql = "p_oa_SupplierOrderSync $id";
        $db = Yii::$app->db;
        $res = $db->createCommand($sql)->execute();
        if(!$res) {
            return '同步失败!';
        }
        return '同步成功!';
    }

    /*
     * @brief 付款
     * @param $id int orderId
     * @return mixed
     */
    public function actionPay($id)
    {
        $sql = "update oa_supplierOrder set paymentStatus='已付款' where id=$id";
        $db = Yii::$app->db;
        $res = $db->createCommand($sql)->execute();
        if(!$res) {
            return '付款失败！';
        }
        return '付款成功！';
    }

    /**
     * @brief 发货
     * @param $id int orderId
     * @return mixed
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
     * @param $id int orderId
     * @return mixed
     */
    public function actionInputExpress($id)
    {
        $order = OaSupplierOrder::findOne($id);
        $billNumber = $order->billNumber;
        $expressNumber = $order->expressNumber;
        $sql = "update cg_stockOrderM  set logisticOrderNo='$expressNumber' where BillNumber='$billNumber'";
        $db = Yii::$app->db;
        $res = $db->createCommand($sql)->execute();
        if(!$res) {
            return '导入失败！';
        }
        return '导入成功！';
    }

    /**
     * @brief 导出采购单明细
     * @param $id
     */
    public function actionExportDetail($id)
    {
        $order = OaSupplierOrder::findOne($id);
        $goodsName = $order->billNumber;
        $sql = "p_oa_exportOrderDetail $id";
        $db = Yii::$app->db;
        $ret = $db->createCommand($sql)->queryAll();

        //表头
        $heard_name = [
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

        $excel = new \PHPExcel();
        $file_name = $goodsName .'-'. date('Y-m-d') . '.xlsx';
        $sheet_num = 0;
        $excel->getActiveSheetIndex($sheet_num);
        $excel->getActiveSheet()->setTitle('采购明细');
        header('Content-type: text/xlsx');
        header('Content-Disposition: attachment;filename=' . $file_name . ' ');
        header('Cache-Control: max-age=0');

        //一个单元格一个单元格写入表头
        foreach ($heard_name as $index => $name) {
            $excel->getActiveSheet()->setCellValue(PHPExcelTools::stringFromColumnIndex($index) . '1', $name);

        }

        //按单元格写入每行数据
        foreach ($ret as $row_num => $row) {
            if (!\is_array($row)) {
                return;
            }

            $cell_num = 0;
            foreach ($row as $index => $name) {
                $excel->getActiveSheet()->setCellValue(PHPExcelTools::stringFromColumnIndex($cell_num) . ($row_num + 2), $name);
                ++$cell_num;
            }

        }
        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');
    }


    /**
     * @brief 接受发货
     * @return mixed
     */

    public function actionInputDeliveryOrder()
    {
        $request = Yii::$app->request;
        if(!$request->isPost) {
            return;
        }
        $file  = new UploadFile();
        $file->excelFile = UploadedFile::getInstance($file,'excelFile');
        if ($file->upload()) {
            return '上传成功！';
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

}
