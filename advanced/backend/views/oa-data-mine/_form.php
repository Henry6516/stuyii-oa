<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OaDataMine */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oa-data-mine-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'proId')->textInput() ?>

    <?= $form->field($model, 'platForm')->textInput() ?>

    <?= $form->field($model, 'progress')->textInput() ?>

    <?= $form->field($model, 'creator')->textInput() ?>

    <?= $form->field($model, 'createTime')->textInput() ?>

    <?= $form->field($model, 'updateTime')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
