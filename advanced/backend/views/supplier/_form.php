<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplier */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oa-supplier-form">

    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'id' => 'supplier-info',
        'options' => ['class' => 'form-horizontal box-body'],
        'enableAjaxValidation' => false,
        'fieldConfig' => [
            'template' => '{label}<div class="col-lg-6">{input}{error}</div>',
            'labelOptions' => ['class' => 'col-sm-1 control-label'],
            'inputOptions' => ['class' => 'form-control']
        ]
    ]); ?>

    <?= $form->field($model, 'supplierName')->widget(\kartik\select2\Select2::classname(), [
        'data' => $data,
        'options' => ['placeholder' => '--请选择供应商--'],
    ])->label('<span style="color: red">供应商名称</span>') ?>

    <?= $form->field($model, 'purchase')->textInput()->label('<span style="color: red">线下采购</span>') ?>

    <?= $form->field($model, 'contactPerson1')->textInput() ?>
    <?= $form->field($model, 'phone1')->textInput() ?>


    <?= $form->field($model, 'contactPerson2')->textInput() ?>

    <?= $form->field($model, 'phone2')->textInput() ?>

    <?= $form->field($model, 'address')->textInput() ?>
    <?= $form->field($model, 'paymentDays')->widget(\kartik\select2\Select2::classname(), [
        'data' => ['无' => '无', '1个月' => '1个月', '2个月' => '2个月', '3个月' => '3个月', '半年' => '半年', '一年' => '一年'],
        'options' => ['placeholder' => '--请选择账期--'],
    ]) ?>

    <?= $form->field($model, 'payChannel')->widget(\kartik\select2\Select2::classname(), [
        'data' => ['线上' => '线上', '线下' => '线下'],
        'options' => ['placeholder' => '--请选择付款渠道--'],
    ]) ?>

    <?= $form->field($model, 'link1')->textInput() ?>

    <?= $form->field($model, 'link2')->textInput() ?>

    <?= $form->field($model, 'link3')->textInput() ?>

    <?= $form->field($model, 'link4')->textInput() ?>

    <?= $form->field($model, 'link5')->textInput() ?>

    <?= $form->field($model, 'link6')->textInput() ?>


    <div class="form-group text-center col-lg-6">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '编辑', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php //echo Html::a('返回列表', ['index'], ['class' =>  'btn btn-default' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
