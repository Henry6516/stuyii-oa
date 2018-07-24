<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierGoodsSku */

$this->title = 'Create Oa Supplier Goods Sku';
$this->params['breadcrumbs'][] = ['label' => 'Oa Supplier Goods Skus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-supplier-goods-sku-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
