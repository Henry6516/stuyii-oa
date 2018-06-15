<?php
/**
 * @desc PhpStorm.
 * @author: turpure
 * @since: 2018-06-13 14:34
 */


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-role-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'role')->textInput(['readonly'=>true]) ?>
            <?= $form->field($model, 'plat')->widget(Select2::classname(), [
                'data' => $plats,
                'options' => ['placeholder' => '--请选择该角色对应的平台--',
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
