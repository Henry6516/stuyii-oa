<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaSupplierGoodsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '供应商产品管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-supplier-goods-index">

    <p>
        <?= Html::a('创建产品', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
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
                ],
            ],
            'supplier',
            'purchaser',
            'goodsCode',
            'goodsName',
            'supplierGoodsCode',
            'createdTime',
            'updatedTime',


        ],
    ]); ?>
</div>
