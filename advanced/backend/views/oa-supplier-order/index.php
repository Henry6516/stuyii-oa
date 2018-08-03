<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaSupplierOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '供应商订单';
$this->params['breadcrumbs'][] = $this->title;
$queryUrl = Url::toRoute(['query']);
?>
<div class="oa-supplier-order-index" style="margin-top: 1%;">

    <div style="margin-top: 1%;margin-bottom: 1%">

        <div class="btn-group">
            <button type="button" class="btn  btn-info">批量操作</button>
            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li role="separator" class="divider"></li>
                <li><?= Html::a('创建订单', ['create'], ['class' => '']) ?></li>
            </ul>
        </div>
        <?= '<a target="_blank" href='. $queryUrl. ' type="button" class="btn btn-danger">同步采购单</a>' ?>

    </div>
    <?php try {echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'kartik\grid\ActionColumn',
                'dropdown' => true,
                'dropdownButton' => [
                        'label' => '操作',
                    'class' => 'action btn btn-default',
                    'data-toggle' => 'dropdown',
                    'aria-haspopup' => 'true',
                    'aria-expanded' => 'false'

                ],
                'template' => '{view} {update} {sync} {pay} {delivery} {inputExpress} {inputDeliveryOrder} {check} ',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return "<li><a target='_blank' href=$url>订单明细</a></li>";
                    },
                    'update' => function ($url, $model) {
                        return "<li><a target='_blank' href=$url>编辑订单</a></li>";
                    },
                    'sync' => function () {
                        return '<li><a href="#">同步普源数据</a></li>';
                    },
                    'delivery' => function () {
                        return '<li><a href="#">发货</a></li>';
                    },
                    'pay' => function () {
                        return '<li><a href="#">付款</a></li>';
                    },
                    'inputExpress' => function () {
                        return '<li><a href="#">导入物流单号</a></li>';
                    },
                    'check' => function () {
                        return '<li><a href="#">审核单据</a></li>';
                    },
                    'inputDeliveryOrder' => function () {
                        return '<li><a href="#">导入发货单</a></li>';
                    },
                ],
            ],
            'supplierName',
            'goodsCode',
            'billNumber',
            'billStatus',
            'purchaser',
            'syncTime',
            'totalNumber',
            'amt',
            'expressNumber',
            'paymentStatus',
        ],
    ]);}
    catch (Exception  $why) {
        throw new \Exception($why);
    }
     ?>
</div>

<style>
    .open { /* Display the dropdown on hover */
        position:absolute;
    }

    .open > button {
        position: relative;
        bottom: 5px;
    }
</style>



