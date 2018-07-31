<?php

use yii\helpers\Html;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaSupplierOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '供应商订单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-supplier-order-index" style="margin-top: 1%;">

    <form>
        <div class="row">
            <div class="col-sm-2">
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon1">供应商名称</span>
                    <input type="text" class="form-control" placeholder="">
                </div><!-- /input-group -->
            </div><!-- /.col-lg-3 -->
            <div class="col-sm-2">
<!--                    <span class="input-group-addon" id="sizing-addon1">下单时间</span>-->
                    <div class="input-group input-daterange">
                        <input type="text" class="form-control" value="2012-04-05">
                        <div class="input-group-addon">下单</div>
                        <input type="text" class="form-control" value="2012-04-19">
                    </div>
            </div><!-- /.col-lg-3 -->
            <div class="col-sm-2">
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon1">产品编码</span>
                    <input type="text" class="form-control" placeholder="">
                </div><!-- /input-group -->
            </div><!-- /.col-lg-3 -->
            <div class="col-sm-2">
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon1">SKU</span>
                    <input type="text" class="form-control" placeholder="">
                </div><!-- /input-group -->
            </div><!-- /.col-lg-3 -->
            <div class="col-sm-2">
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon1">采购单号</span>
                    <input type="text" class="form-control" placeholder="">
                </div><!-- /input-group -->
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary">查询</button>
            </div>
        </div><!-- /.row -->
    </form>

    <div style="margin-top: 1%;margin-bottom: 1%">

        <div class="btn-group">
            <button type="button" class="btn  btn-info">常用操作</button>
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

<?php


$js = <<< JS

$('.input-daterange input').each(function() {
    $(this).datepicker('clearDates');
});


JS;

$this->registerJs($js);

?>