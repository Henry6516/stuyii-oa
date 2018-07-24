<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oa-supplier-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'supplierName') ?>

    <?= $form->field($model, 'contactPerson1') ?>

    <?= $form->field($model, 'phone1') ?>

    <?= $form->field($model, 'contactPerson2') ?>

    <?php // echo $form->field($model, 'phone2') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'link1') ?>

    <?php // echo $form->field($model, 'link2') ?>

    <?php // echo $form->field($model, 'link3') ?>

    <?php // echo $form->field($model, 'link4') ?>

    <?php // echo $form->field($model, 'link5') ?>

    <?php // echo $form->field($model, 'link6') ?>

    <?php // echo $form->field($model, 'paymentDays') ?>

    <?php // echo $form->field($model, 'payChannel') ?>

    <?php // echo $form->field($model, 'purchase') ?>

    <?php // echo $form->field($model, 'createtime') ?>

    <?php // echo $form->field($model, 'updatetime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
