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
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon1">下单时间</span>
                    <input type="text" class="form-control" placeholder="">
                </div><!-- /input-group -->
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
                'template' => '{view} {update} {sync} {pay} {delivery} {inputExpress} {inputDeliveryOrder} {check} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
//                        return Html::a('<span class="glyphicon glyphicon-eye-open"
//                                title="产品明细"></span>', $url, ['data-pjax' => 0, 'target' => '_blank']);
                        return "<li><a target='_blank' href=$url>订单明细</a></li>";
                    },
                    'update' => function ($url, $model) {

//                        return Html::a('<span class="glyphicon glyphicon-pencil"
//                                title="编辑"></span>',$url, ['data-pjax' => 0, 'target' => '_blank']);
                        return '<li><a href="#">fef</a></li>';
                    },
                    'delete' => function () {
//                        return Html::a('<span class="delete-row glyphicon glyphicon-trash"
//                                title= "删除"></span>', 'javascript:void(0);', ['data-pjax' => 0,]);
                        return '<li><a href="#">fe</a></li>';
                    },
                    'sync' => function () {
//                        return Html::a('<span class="delete-row glyphicon glyphicon-share"
//                                title= "同步"></span>', 'javascript:void(0);', ['data-pjax' => 0,]);
                        return '<li><a href="#">Acfeftion</a></li>';
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


</style>
