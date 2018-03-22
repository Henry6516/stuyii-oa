<?php

use kartik\widgets\ActiveForm;

$this->title = '数据详情';

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


<?= $form->field($mine,'proName')?>
<?= $form->field($mine,'tags')?>
<?= $form->field($mine,'parentId')?>
<?= $form->field($mine,'childId')?>
<?= $form->field($mine,'color')?>
<?= $form->field($mine,'proSize')?>
<?= $form->field($mine,'quantity')?>
<?= $form->field($mine,'price')?>
<?= $form->field($mine,'msrPrice')?>
<?= $form->field($mine,'shipping')?>
<?= $form->field($mine,'shippingWeight')?>
<?= $form->field($mine,'shippingTime')?>
<?=
$form->field($mine,'extra_image0',[
        'template' => "
<div class='row'>{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-8\">{error}</div><div>
<div class='col-lg-2'><img src='{$mine->extra_image0}' width=50 height='50'></div>
</div>
" ])

)?>





<?php ActiveForm::end() ?>
