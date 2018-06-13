<?php
/**
 * @desc PhpStorm.
 * @author: turpure
 * @since: 2018-06-13 14:34
 */


use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="department-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'name')->textInput() ?>
            <?= $form->field($model, 'plat')->textInput(['id' => 'plat']) ?>
            <?= $form->field($model, 'store')->textInput(['id' => 'store']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
