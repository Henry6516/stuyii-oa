<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OaGoods */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oa-goods-form">

    <?php $form = ActiveForm::begin([
        'fieldConfig'=>[
            'template'=> "{label}\n<div >{input}</div>\n{error}",
        ]
    ]); ?>


    <?= $form->field($model, 'img')->textInput() ?>

    <?= $form->field($model, 'cate')->textInput() ?>

    <?= $form->field($model, 'subCate')->textInput() ?>

    <?= $form->field($model, 'vendor1')->textInput() ?>

    <?= $form->field($model, 'origin1')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$js =<<< JS
//监听备货按钮事件
$('#oaforwardgoods-stockup').on('click',function(e) {
     if($(this).is(":checked")){
        if('$canStock' === 'no'){
            alert('已经超过本月备货数量！不能继续备货！');
            e.preventDefault();
        }    
     }
})

JS;

$this->registerJs($js);

?>
