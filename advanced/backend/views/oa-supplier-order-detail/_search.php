<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierOrderDetailSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oa-supplier-order-detail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'orderId') ?>

    <?= $form->field($model, 'sku') ?>

    <?= $form->field($model, 'image') ?>

    <?= $form->field($model, 'supplierGoodsSku') ?>

    <?php // echo $form->field($model, 'goodsName') ?>

    <?php // echo $form->field($model, 'property1') ?>

    <?php // echo $form->field($model, 'property2') ?>

    <?php // echo $form->field($model, 'property3') ?>

    <?php // echo $form->field($model, 'purchaseNumber') ?>

    <?php // echo $form->field($model, 'purchasePrice') ?>

    <?php // echo $form->field($model, 'deliveryNumber') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
