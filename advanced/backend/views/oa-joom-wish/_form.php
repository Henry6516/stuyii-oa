<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OaJoomWish */
/* @var $form yii\widgets\ActiveForm */
?>

<?php

$css =<<< CSS

input {
    size: 40px;
}

CSS;

$this->registerCss($css);
?>

<div class="oa-joom-wish-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'greater_equal')->textInput() ?>

    <?= $form->field($model, 'less')->textInput() ?>

    <?= $form->field($model, 'added_price')->textInput() ?>

    <?= $form->field($model, 'createDate')->textInput(['readOnly' => true]) ?>

    <?= $form->field($model, 'updateDate')->textInput(['readOnly' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>



