<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierGoodsSku */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oa-supplier-goods-sku-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'supplierGoodsId')->textInput() ?>

    <?= $form->field($model, 'sku')->textInput() ?>

    <?= $form->field($model, 'property1')->textInput() ?>

    <?= $form->field($model, 'property2')->textInput() ?>

    <?= $form->field($model, 'property3')->textInput() ?>

    <?= $form->field($model, 'costPrice')->textInput() ?>

    <?= $form->field($model, 'purchasPrice')->textInput() ?>

    <?= $form->field($model, 'weight')->textInput() ?>

    <?= $form->field($model, 'image')->textInput() ?>

    <?= $form->field($model, 'lowestPrice')->textInput() ?>

    <?= $form->field($model, 'purchasNumber')->textInput() ?>

    <?= $form->field($model, 'supplierGoodsSku')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
