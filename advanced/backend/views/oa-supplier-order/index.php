<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaSupplierOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '供应商订单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-supplier-order-index">

    <p>
        <?= Html::a('创建订单', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {sync}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open" 
                                title="查看SKU"></span>', $url, ['data-pjax' => 0, 'target' => '_blank']);
                    },
                    'update' => function ($url, $model) {

                        return Html::a('<span class="glyphicon glyphicon-pencil" 
                                title="编辑"></span>',$url, ['data-pjax' => 0, 'target' => '_blank']);
                    },
                    'delete' => function () {
                        return Html::a('<span class="delete-row glyphicon glyphicon-trash"
                                title= "删除"></span>', 'javascript:void(0);', ['data-pjax' => 0,]);
                    },
                    'sync' => function () {
                        return Html::a('<span class="delete-row glyphicon glyphicon-share"
                                title= "同步"></span>', 'javascript:void(0);', ['data-pjax' => 0,]);
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
    ]); ?>
</div>
