<?php

use kartik\widgets\ActiveForm;

$this->title = '数据详情';

?>

<?php
Modal::begin([
    'id' => 'templates-modal',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
    'size' => "modal-xl",
    'options'=>[
        'data-backdrop'=>'static',//点击空白处不关闭弹窗
        'data-keyboard'=>false,
    ],
]);
//echo
Modal::end();
?>


<?php
echo "<div><img src='{$mine->MainImage}' width=60 height=60}></div>"
?>


<?php $form = ActiveForm::begin([
    'id' => 'detail-form',
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-8\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
    ]
]);
?>
<div class="blockTitle" >
    <span>基本信息</span>
</div>

<?= $form->field($mine,'proName')?>
<?= $form->field($mine,'tags')?>
<?= $form->field($mine,'parentId')?>
<?= $form->field($mine,'childId')?>
<?= $form->field($mine,'description')->textarea(['style' => "width: 885px; height: 282px;"])?>

<?= $form->field($mine,'extra_image0',['template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-4\"><img src='{$mine->extra_image0}' width='50' height='50'></div>"])->textInput(['style' => 'margin-bottom:2%']); ?>
<?= $form->field($mine,'extra_image1',['template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-4\"><img src='{$mine->extra_image1}' width='50' height='50'></div>"])->textInput(['style' => 'margin-bottom:2%']); ?>
<?= $form->field($mine,'extra_image2',['template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-4\"><img src='{$mine->extra_image2}' width='50' height='50'></div>"])->textInput(['style' => 'margin-bottom:2%']); ?>
<?= $form->field($mine,'extra_image3',['template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-4\"><img src='{$mine->extra_image3}' width='50' height='50'></div>"])->textInput(['style' => 'margin-bottom:2%']); ?>
<?= $form->field($mine,'extra_image4',['template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-4\"><img src='{$mine->extra_image4}' width='50' height='50'></div>"])->textInput(['style' => 'margin-bottom:2%']); ?>
<?= $form->field($mine,'extra_image5',['template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-4\"><img src='{$mine->extra_image5}' width='50' height='50'></div>"])->textInput(['style' => 'margin-bottom:2%']); ?>
<?= $form->field($mine,'extra_image6',['template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-4\"><img src='{$mine->extra_image6}' width='50' height='50'></div>"])->textInput(['style' => 'margin-bottom:2%']); ?>
<?= $form->field($mine,'extra_image7',['template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-4\"><img src='{$mine->extra_image7}' width='50' height='50'></div>"])->textInput(['style' => 'margin-bottom:2%']); ?>
<?= $form->field($mine,'extra_image8',['template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-4\"><img src='{$mine->extra_image8}' width='50' height='50'></div>"])->textInput(['style' => 'margin-bottom:2%']); ?>
<?= $form->field($mine,'extra_image9',['template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-4\"><img src='{$mine->extra_image9}' width='50' height='50'></div>"])->textInput(['style' => 'margin-bottom:2%']); ?>
<?= $form->field($mine,'extra_image10',['template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-4\"><img src='{$mine->extra_image10}' width='50' height='50'></div>"])->textInput(['style' => 'margin-bottom:2%']); ?>

<div class="blockTitle" >
    <span>多属性信息</span>
</div>
<div>
    <button class="btn">多属性</button>
</div>

<?php ActiveForm::end() ?>


<style>
    .blockTitle {
        font-size: 16px;
        background-color: #f7f7f7;
        border-top: 0.5px solid #eee;
        border-bottom: 0.5px solid #eee;
        padding: 2px 12px;
        margin-left: -5px;
        margin-bottom: 1%;
    }

    .blockTitle span {
        margin-top: 20px;
        font-weight: bold;
    }
</style>


