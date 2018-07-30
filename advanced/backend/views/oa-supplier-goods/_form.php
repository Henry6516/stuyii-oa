<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierGoods */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oa-supplier-goods-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'supplier')->widget(Select2::className(),[
            'data' => $suppliers,
            'options' => ['class'=> 'oa-supplier','placeholder' => '--供应商--'],
            'pluginOptions' => [
                    'allowClear' => true,
            ],
    ]); ?>

    <?= $form->field($model, 'purchaser')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'goodsCode')->textInput() ?>

    <?= $form->field($model, 'goodsName')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'supplierGoodsCode')->textInput() ?>

    <?= $form->field($model, 'createdTime')->textInput(['readonly'=> true]) ?>

    <?= $form->field($model, 'updatedTime')->textInput(['readonly'=> true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord?'创建':'更新', ['class' => $model->isNewRecord?'btn btn-success':'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$getPurchaserUrl = Url::toRoute(['get-purchaser']);
$js = <<< JS

/*
set purchaser name based on supplier name
 */
$('.oa-supplier').change(function() {
  var id = $(this).val();
  $.get('$getPurchaserUrl',{id:id},function(res) {
     $('#oasuppliergoods-purchaser').val(res);
  });
})

/*
set goods name based on goods name
 */
$('#oasuppliergoods-goodscode').change(function() { 
  $('#oasuppliergoods-goodsname').val ('566');
})
    
JS;

$this->registerJs($js);
?>

<style>
    div.required label:before {
        content: "*";
        color: red;
    }
</style>
