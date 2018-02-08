<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OaJoomWish */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oa-joom-wish-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nid')->textInput() ?>

    <?= $form->field($model, 'greater_equal')->textInput() ?>

    <?= $form->field($model, 'less')->textInput() ?>

    <?= $form->field($model, 'added_price')->textInput() ?>

    <?= $form->field($model, 'createDate')->textInput() ?>

    <?= $form->field($model, 'updateDate')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
