<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierOrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oa-supplier-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'supplierName') ?>

    <?= $form->field($model, 'goodsCode') ?>

    <?= $form->field($model, 'billNumber') ?>

    <?= $form->field($model, 'billStatus') ?>

    <?php // echo $form->field($model, 'purchaser') ?>

    <?php // echo $form->field($model, 'syncTime') ?>

    <?php // echo $form->field($model, 'totalNumber') ?>

    <?php // echo $form->field($model, 'amt') ?>

    <?php // echo $form->field($model, 'expressNumber') ?>

    <?php // echo $form->field($model, 'paymentStatus') ?>

    <?php // echo $form->field($model, 'orderTime') ?>

    <?php // echo $form->field($model, 'createdTime') ?>

    <?php // echo $form->field($model, 'updatedTime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
