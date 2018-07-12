<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput() ?>
    <?= $form->field($model, 'department')->textInput() ?>
    <?= $form->field($model, 'isLeader')->textInput() ?>
    <?= $form->field($model, 'leaderName')->textInput() ?>
    <?= $form->field($model, 'store')->widget(Select2::classname(), [
        'data' => $store,
        'options' => ['placeholder' => '--请分配仓库--',
            'multiple' => true,
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>
    <?= $form->field($model, 'canStockUp')->textInput() ?>
    <?= $form->field($model, 'mapPersons')->widget(Select2::classname(), [
        'data' => $person,
        'options' => ['placeholder' => '--请选择人员--',
            'multiple' => true,
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
