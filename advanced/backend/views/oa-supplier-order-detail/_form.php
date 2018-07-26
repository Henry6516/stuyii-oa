<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierOrderDetail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oa-supplier-order-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'orderId')->textInput() ?>

    <?= $form->field($model, 'sku')->textInput() ?>

    <?= $form->field($model, 'image')->textInput() ?>

    <?= $form->field($model, 'supplierGoodsSku')->textInput() ?>

    <?= $form->field($model, 'goodsName')->textInput() ?>

    <?= $form->field($model, 'property1')->textInput() ?>

    <?= $form->field($model, 'property2')->textInput() ?>

    <?= $form->field($model, 'property3')->textInput() ?>

    <?= $form->field($model, 'purchaseNumber')->textInput() ?>

    <?= $form->field($model, 'purchasePrice')->textInput() ?>

    <?= $form->field($model, 'deliveryNumber')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
