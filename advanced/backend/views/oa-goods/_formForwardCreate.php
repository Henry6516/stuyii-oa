<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model backend\models\OaGoods */
/* @var $form yii\widgets\ActiveForm */

$createUrl = Url::toRoute(['oa-goods/forward-create','type'=>'check', ]);

$js = <<<JS

function checkNumber(ele) {
  //ajax 提交表单
  ele.on('click', function() {
  
  if(!$('#oaforwardgoods-stockup').is(":checked")) {
    if('$canCreate' === 'no') {
      alert('已经超过本月数量限制！');
      return false;
    }
  }
  var form = $('#create-form');
  $.ajax({
      url:'$createUrl',
      type: 'post',
      data: form.serialize(),
      success: function(ret) {
        alert(ret);
        window.location.reload();
      }
  });
})
  
}

//create
checkNumber($('#create-btn'));
checkNumber($('#create-to-check'));



JS;
$this->registerJs($js);

$getSubCateUrl = Url::toRoute(['oa-goods/forward-create','typeid'=>1, ]);


?>

<div class="oa-form">

    <?php $form = ActiveForm::begin(
        [
            'id' => 'create-form',
            'method' => 'post',
        ]
    ); ?>

    <?php echo  $form->field($model, 'img',['template' => "<font color='red'>*{label}</font>\n<div >{input}</div>\n<div >{error}</div>",])->textInput(['placeholder' => '--必填--']) ?>

    <?= $form->field($model,'cate',['template' => "<font color='red'>*{label}</font>\n<div >{input}</div>\n<div >{error}</div>",])->dropDownList($model->getCatList(0),
        [
            'prompt'=>'--请选择父类--',
            'onchange'=>'
           
//            $("select#oaforwardgoods-subcate").html("");
            $.get("'.$getSubCateUrl.'&pid="+$(this).val(),function(data){
               var str="";
              $("select#oaforwardgoods-subcate").children("option").remove();
              $.each(data,function(k,v){
                    str+="<option value="+v+">"+v+"</option>";
                    });
                $("select#oaforwardgoods-subcate").html(str);
            });',
        ]) ?>

    <?= $form->field($model,'subCate',['template' => "<font color='red'>*{label}</font>\n<div >{input}</div>\n<div >{error}</div>",])->dropDownList($model->getCatList($model->cate),
        [
            'prompt'=>'--请选择子类--',

        ]) ?>

    <?php echo  $form->field($model, 'vendor1',['template' => "<font color='red'>*{label}</font>\n<div >{input}</div>\n<div >{error}</div>",])->textInput(['placeholder' => '--必填--']) ?>
    <?php echo  $form->field($model, 'vendor2')->textInput() ?>
    <?php echo  $form->field($model, 'vendor3')->textInput() ?>

    <?php echo  $form->field($model, 'origin1')->textInput(['placeholder' => '--选填--']) ?>
    <?php echo  $form->field($model, 'origin2')->textInput(['placeholder' => '--选填--']) ?>
    <?php echo  $form->field($model, 'origin3')->textInput(['placeholder' => '--选填--']) ?>

    <?php echo  $form->field($model, 'salePrice')->textInput(['placeholder' => '--选填--']) ?>
    <?php echo  $form->field($model, 'hopeSale')->textInput(['placeholder' => '--选填--']) ?>

    <?php echo  $form->field($model, 'hopeRate')->textInput(['placeholder' => '--选填--']) ?>
    <?php echo  $form->field($model, 'hopeWeight')->textInput(['placeholder' => '--选填--']) ?>
    <?php echo  $form->field($model, 'hopeCost')->textInput(['placeholder' => '--选填--']) ?>

    <?php echo  $form->field($model, 'hopeMonthProfit')->textInput(['readonly' => 'true','placeholder' => '--自动计算--']) ?>

    <?php echo  $form->field($model, 'stockUp')->checkbox()?>


    <div class="form-group">
        <?= Html::button('创建', ['id' => 'create-btn','class' => 'btn btn-primary']) ?>
        <?= Html::button('创建并提交审批', ['id' => 'create-to-check', 'class' => 'btn btn-info']) ?>
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
