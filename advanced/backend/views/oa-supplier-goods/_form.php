<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierGoods */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oa-supplier-goods-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'supplier')->textInput() ?>

    <?= $form->field($model, 'purchaser')->textInput() ?>

    <?= $form->field($model, 'goodsCode')->textInput() ?>

    <?= $form->field($model, 'goodsName')->textInput() ?>

    <?= $form->field($model, 'supplierGoodsCode')->textInput() ?>

    <?= $form->field($model, 'createdTime')->textInput() ?>

    <?= $form->field($model, 'updatedTime')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
