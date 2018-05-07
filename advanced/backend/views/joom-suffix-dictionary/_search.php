<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OaJoomSuffixSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oa-joom-suffix-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'nid') ?>

    <?= $form->field($model, 'joomName') ?>

    <?= $form->field($model, 'joomSuffix') ?>

    <?= $form->field($model, 'imgCode') ?>

    <?= $form->field($model, 'mainImg') ?>

    <?php // echo $form->field($model, 'skuCode') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
