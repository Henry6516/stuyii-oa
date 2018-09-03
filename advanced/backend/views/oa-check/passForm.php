<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\OaGoods */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oa-tocheck-form">

    <?php $form = ActiveForm::begin([
        'id' => 'pass-form',
        'action' => ['pass'],
    ]); ?>

    <?= $form->field($model, 'approvalNote')->textInput() ?>
    <?= $form->field($model, 'nid',['labelOptions' => ['hidden'=>"hidden"]])->hiddenInput() ?>
    <?php
        if($mid) {
            echo $form->field($model, 'dictionaryName')->widget(Select2::classname(),[
                'name' => 'DictionaryName',
                'id' => 'dictionary-name',
                //'value' => $bannedNames,
                'data' => $dictionaryName,
                'maintainOrder' => true,
                'options' => ['placeholder' => '--可多选--', 'multiple' => true],
                'pluginOptions' => [
                    'tags' => true,
                    'maximumInputLength' => 5
                ],
            ]);
        }
    ?>

    <div class="form-group">
        <?= Html::button('通过审核', ['id'=>'pass-btn','class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    $js = <<<JS
        $('#pass-btn').on('click',function () {
            $.post($('#pass-form').attr('action'), $('form').serialize(), function(msg){
                krajeeDialog.alert(msg, function(res) {
                    location.reload();
                });
            });
        });
JS;
    $this->registerJs($js);
    ?>

</div>

