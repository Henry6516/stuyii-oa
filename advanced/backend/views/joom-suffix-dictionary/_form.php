<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OaJoomSuffix */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oa-joom-suffix-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'joomName')->textInput() ?>

    <?= $form->field($model, 'imgCode')->textInput() ?>

    <?= $form->field($model, 'mainImg')->textInput() ?>

    <?= $form->field($model, 'skuCode')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
