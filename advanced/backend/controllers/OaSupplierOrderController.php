<?php

namespace backend\controllers;

use backend\models\OaSupplierOrderDetail;
use Yii;
use backend\models\OaSupplierOrder;
use backend\models\OaSupplierOrderSearch;
use backend\models\OaSupplierGoodsSkuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\unitools\PHPExcelTools;
use yii\data\ActiveDataProvider;
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

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
            'query' => OaSupplierOrderDetail::find()->where(['orderId'=>$id]),
            'pagination' => ['pageSize'=>200]
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
            return $this->redirect(['view', 'id' => $model->id]);
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
     * get order from shopElf
     * @return mixed
     */
    public function actionQueryOrder()
    {
        $db = Yii::$app->db;
        if(Yii::$app->request->isPost) {
            $query = Yii::$app->request->post();
            return $query;
        }

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



    public function actionExport()
    {
        //表头
        $heard_name = [
            'Parent Unique ID',
            '*Product Name',
        ];

        $excel = new \PHPExcel();
        $file_name = '-contract-' . date('d-m-Y-His') . '.xlsx';
        $sheet_num = 0;
        $excel->getActiveSheetIndex($sheet_num);
        header('Content-type: text/xlsx');
        header('Content-Disposition: attachment;filename=' . $file_name . ' ');
        header('Cache-Control: max-age=0');

        //一个单元格一个单元格写入表头
        foreach ($heard_name as $index => $name)
        {
            $excel->getActiveSheet()->setCellValue(PHPExcelTools::stringFromColumnIndex($index) . '1', $name);

        }

        //按单元格写入每行数据
//        foreach ($ret as $row_num => $row)
//        {
//            if(!\is_array($row)){
//                return;
//            }
//
//            $cell_num = 0;
//            foreach ($row as $index => $name)
//            {
//                $excel->getActiveSheet()->setCellValue(PHPExcelTools::stringFromColumnIndex($cell_num) .($row_num + 2), $name);
//                ++$cell_num;
//            }
//
//        }
//        $writer =  new \PHPExcel_Writer_Excel5($excel);
        $writer = \PHPExcel_IOFactory::createWriter($excel,'Excel2007');
        $writer->save('php://output');

    }
}
