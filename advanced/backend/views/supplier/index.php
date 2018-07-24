<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaSupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '供应商管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-supplier-index">

    <h1><?php //echo Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加新供应商', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\ActionColumn'],
            'purchase',
            'supplierName',
            'contactPerson1',
            'phone1',
            'contactPerson2',
            'phone2',
            'paymentDays',
            'payChannel',
            'address',
            'link1',
            'link2',
            //'link3',
            //'link4',
            //'link5',
            //'link6',
            //'createtime',
            //'updatetime',


        ],
    ]); ?>
</div>
