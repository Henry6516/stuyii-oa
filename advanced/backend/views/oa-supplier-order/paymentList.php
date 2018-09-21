<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaSupplierOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '付款明细';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="oa-supplier-order-index" style="margin-top: 1%;">


    <?php Pjax::begin(['id' => 'order-table']) ?>
    <?php try {
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'id' => 'supplier-order-view',
            'pjax' => 'true',
            'options' => ['data-pjax' => 'order-table'],
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{payment} ',
                    'buttons' => [
                        'payment' => function ($url, $model, $key) {
                            $id = \backend\models\OaSupplierOrder::findOne(['billNumber' => $model->billNumber])['id'];
                            return Html::a('<span  class="glyphicon glyphicon-flash"></span></a>',
                                Url::to('/oa-supplier-order/payment?id='.$id),
                                ['type' => 'button', 'title' => '前往付款', 'aria-label' => '前往付款']);
                        },
                    ],
                ],
                [
                    'attribute' => 'billNumber',
                    'pageSummary' => 'pageSummary',
                ],
                [
                    'attribute' => 'requestTime',
                    'pageSummary' => false,
                ],
                [
                    'attribute' => 'requestAmt',
                    'pageSummary' => false,
                ],
                [
                    'attribute' => 'paymentStatus',
                    'pageSummary' => false,
                ],
                [
                    'attribute' => 'comment',
                    'pageSummary' => false,
                ],
                [
                    'attribute' => 'paymentTime',
                    'pageSummary' => true,
                ],
                [
                    'attribute' => 'paymentAmt',
                    'pageSummary' => true,
                ],
                [
                    'attribute' => 'unpaidAmt',
                    'format' => 'raw',
                    'pageSummary' => true,
                ],
            ],
        ]);
    } catch (Exception  $why) {
        throw new \Exception($why);
    }
    ?>

    <?php Pjax::end() ?>

</div>






