<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OaJoomWishSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oa-joom-wish-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'nid') ?>

    <?= $form->field($model, 'greater_equal') ?>

    <?= $form->field($model, 'less') ?>

    <?= $form->field($model, 'added_price') ?>

    <?= $form->field($model, 'createDate') ?>

    <?php // echo $form->field($model, 'updateDate') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
