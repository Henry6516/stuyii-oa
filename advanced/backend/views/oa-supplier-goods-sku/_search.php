<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierGoodsSkuSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oa-supplier-goods-sku-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'supplierGoodsId') ?>

    <?= $form->field($model, 'sku') ?>

    <?= $form->field($model, 'property1') ?>

    <?= $form->field($model, 'property2') ?>

    <?php // echo $form->field($model, 'property3') ?>

    <?php // echo $form->field($model, 'costPrice') ?>

    <?php // echo $form->field($model, 'purchasPrice') ?>

    <?php // echo $form->field($model, 'weight') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'lowestPrice') ?>

    <?php // echo $form->field($model, 'purchasNumber') ?>

    <?php // echo $form->field($model, 'supplierGoodsSku') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
