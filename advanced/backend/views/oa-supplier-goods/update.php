<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierGoods */

$this->title = 'Update Oa Supplier Goods: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Oa Supplier Goods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="oa-supplier-goods-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
