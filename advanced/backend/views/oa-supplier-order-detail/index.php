<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaSupplierOrderDetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Oa Supplier Order Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-supplier-order-detail-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Oa Supplier Order Detail', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'orderId',
            'sku',
            'image',
            'supplierGoodsSku',
            //'goodsName',
            //'property1',
            //'property2',
            //'property3',
            //'purchaseNumber',
            //'purchasePrice',
            //'deliveryNumber',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
