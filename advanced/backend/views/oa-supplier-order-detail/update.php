<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierOrderDetail */

$this->title = 'Update Oa Supplier Order Detail: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Oa Supplier Order Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="oa-supplier-order-detail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
