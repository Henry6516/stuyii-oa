<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaSupplierGoodsSkuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Oa Supplier Goods Skus';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-supplier-goods-sku-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Oa Supplier Goods Sku', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'supplierGoodsId',
            'sku',
            'property1',
            'property2',
            //'property3',
            //'costPrice',
            //'purchasPrice',
            //'weight',
            //'image',
            //'lowestPrice',
            //'purchasNumber',
            //'supplierGoodsSku',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
