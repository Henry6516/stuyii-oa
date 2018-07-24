<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierGoodsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oa-supplier-goods-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'supplier') ?>

    <?= $form->field($model, 'purchaser') ?>

    <?= $form->field($model, 'goodsCode') ?>

    <?= $form->field($model, 'goodsName') ?>

    <?php // echo $form->field($model, 'supplierGoodsCode') ?>

    <?php // echo $form->field($model, 'createdTime') ?>

    <?php // echo $form->field($model, 'updatedTime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
