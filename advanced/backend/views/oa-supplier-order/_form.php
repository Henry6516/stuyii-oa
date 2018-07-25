<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oa-supplier-order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'supplierName')->textInput() ?>

    <?= $form->field($model, 'goodsCode')->textInput() ?>

    <?= $form->field($model, 'billNumber')->textInput() ?>

    <?= $form->field($model, 'billStatus')->textInput() ?>

    <?= $form->field($model, 'purchaser')->textInput() ?>

    <?= $form->field($model, 'syncTime')->textInput() ?>

    <?= $form->field($model, 'totalNumber')->textInput() ?>

    <?= $form->field($model, 'amt')->textInput() ?>

    <?= $form->field($model, 'expressNumber')->textInput() ?>

    <?= $form->field($model, 'paymentStatus')->textInput() ?>

    <?= $form->field($model, 'orderTime')->textInput() ?>

    <?= $form->field($model, 'createdTime')->textInput() ?>

    <?= $form->field($model, 'updatedTime')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
