<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\builder\FormGrid;
use kartik\builder\TabularForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\Channel */

$this->title =  '更新平台信息';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '平台信息'), 'url' => ['index']];
$shipping_templates = [
    "template"=>"<div > {label} </div><div class='col-lg-6'>{input}</div>{hint}{error}",
    'labelOptions' => ['class' => 'col-lg-2 control-label']
                ];


$templatesVarUrl = Url::toRoute('templates-var'); // 多属性连接

//创建模态框
use yii\bootstrap\Modal;
Modal::begin([
    'id' => 'templates-modal',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
    'size' => "modal-xl"
]);
//echo
Modal::end();
?>

<div class="channel-update">

    <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a href="#">eBay</a></li>
        <li role="presentation"><a href="#">Wish</a></li>
    </ul>
</div>
</br>
<div class="st">
    <p>
        <?= Html::button('保存当前数据', ['id' => 'save-only','class' =>'btn btn-default']) ?>
        <?= Html::button('保存并完善', ['id' => 'save-complete','class' =>'btn btn-default']) ?>
        <?= Html::button('导出刊登模板', ['id' => 'import-templates','class' =>'btn btn-default']) ?>
    </p>
</div>
</br>

<?php $form = ActiveForm::begin([
    'id' => 'msg-form',
    'options' => ['class'=>'form-horizontal'],
    'enableAjaxValidation'=>false,
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ]
]);
?>

<div class="blockTitle">
    <p >基本信息</p>
</div>
</br>
<?= $form->field($info,'sku')->textInput()?>
<?php
echo '<div class="form-group field-oatemplates-mainpage">
    <label class="col-lg-1 control-label">主图</label>
    <div class="col-lg-3"><input type="text" class="form-control" value="https://www.tupianku.com/view/full/10023/'.$info->sku.'-_0_.jpg"></div>
    <div class="col-lg=1"> 
    <a target="_blank" href="https://www.tupianku.com/view/full/10023/'.$info->sku.'-_0_.jpg">
    <img src="https://www.tupianku.com/view/full/10023/'.$info->sku.'-_0_.jpg" width="50" height="50">
    </a>
    </div>
</div>'
;?>
<?= $form->field($info,'extraPage')->textarea(['style'=>'display:']); ?>
<?php
echo '<div class="images">';
$images = json_decode($info->extraPage,true)['images'];
foreach($images as $key=>$image){
    echo '<div class="form-group all-images">
    
    <label class="col-lg-1"></label>
    <strong class="serial">#'.($key+1).'</strong>
    <div class="col-lg-3"><input  type="text" class="form-control extra-images" value="'.$image.'"></div>
    <div class="col-lg=1">
    <button  class="btn add-images">增加</button>
    <button  class="btn btn-error remove-image">删除</button>
    <button class="btn up-btn btn-error">上移动</button>
    <button class="btn down-btn btn-error">下移动</button>
    <a target="_blank" href="'.$image.'">
    <img src="'.$image.'" width="50" height="50">
    </a>
    </div>
</div>';
}
echo '</div>';
?>
</br>
<?= $form->field($info,'location')->textInput(['value'=>'ShangHai']); ?>
<?= $form->field($info,'country')->textInput(['value' => 'CN' ]); ?>
<?= $form->field($info,'postCode')->textInput(); ?>
<?= $form->field($info,'prepareDay')->textInput(['value' => '3' ]); ?>

<div class="blockTitle">
    <p > 站点组</p>
</div>
</br>
<?= $form->field($info,'site')->textInput(['value' => '美国站' ]); ?>
<div class="blockTitle">
    <p > 多属性</p>
</div>
</br>
<div>
<a  data-toggle="modal" data-target="#templates-modal" class=" var-btn btn btn-default ">设置多属性</a>
    </div>
</br>
<div class="blockTitle">
    <p > 主信息</p>
</div>
</br>
<div>
<?= $form->field($info,'listedCate')->textInput(); ?>
<?= $form->field($info,'listedSubcate')->textInput(); ?>
<?= $form->field($info,'listedSubcate')->textInput(); ?>
<?= $form->field($info,'title')->textInput(); ?>
<?= $form->field($info,'subTitle')->textInput(); ?>
<?= $form->field($info,'description')->textarea(['rows'=>6]); ?>
<?= $form->field($info,'quantity')->textInput(); ?>
<?= $form->field($info,'nowPrice')->textInput(); ?>
<?= $form->field($info,'UPC')->textInput(['value' => 'Does not apply']); ?>
<?= $form->field($info,'EAN')->textInput(['value' => 'Does not apply']); ?>
</div>

<div class="blockTitle">
    <p >物品属性</p>
</div>
</br>
<div>
    <?= $form->field($info,'specifics')->textarea(['row' =>6]); ?>
    <?php
    echo
    '<div class="row"><div class="col-lg-6"><table class="specifics-tab table table-hover">
    <thead>
    <tr>
    <th>属性名称</th>
    <th>属性内容</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <th>Brand</th>
    <td><input size="40"></td>
    </tr>
    <tr>
    <th>Type</th>
    <td><input size="40"></td>
    </tr>
    <tr>
    <th>Material</th>
    <td><input size="40"></td>
    </tr>
    <tr>
    <th>IntendedUse</th>
    <td><input size="40"></td>
    </tr>
    <tr>
    <th>unit</th>
    <td><input size="40"></td>
    </tr>
    <tr>
    <th>bundleListing</th>
    <td><input size="40"></td>
    </tr>
    <tr>
    <th>shape</th>
    <td><input size="40"></td>
    </tr>
    <tr>
    <th>features</th>
    <td><input size="40"></td>
    </tr>
    <tr>
    <th>regionManufacture</th>
    <td><input size="40"></td>
    </tr>
    </tbody>
    </table>
    <button class=" add-specifics btn btn-default">增加属性</button>
    </div></div>';
    ?>
</div>
</br>
<div class="blockTitle">
    <p >物流设置</p>

</div>
</br
<div>
<div class="row" >
    <div class="col-lg-6">
    <span>境内运输方式</span>
<?=
$form->field($info,'InshippingMethod1',$shipping_templates)->dropDownList($inShippingService,
    [
        'class' => 'col-lg-6',
        'prompt'=>'--境内物流选择--',
    ]
); ?>
<?= $form->field($info,'InFirstCost1',$shipping_templates)->textInput(['placeholder' => '--USD--']); ?>
<?= $form->field($info,'InSuccessorCost1',$shipping_templates)->textInput(['placeholder' => '--USD--']); ?>
        <?=
        $form->field($info,'InshippingMethod2',$shipping_templates)->dropDownList($inShippingService,
            [
                'class' => 'col-lg-6',
                'prompt'=>'--境内物流选择--',
            ]
        ); ?>
<?= $form->field($info,'InFirstCost2',$shipping_templates)->textInput(['placeholder' => '--USD--']); ?>
<?= $form->field($info,'InSuccessorCost2',$shipping_templates)->textInput(['placeholder' => '--USD--']); ?>
    </div>
    <div class="col-lg-6">
    <span>境外运输方式</span>
        <?=
        $form->field($info,'OutshippingMethod1',$shipping_templates)->dropDownList($outShippingService,
            [
                'class' => 'col-lg-6',
                'prompt'=>'--境外物流选择--',
            ]
        ); ?>
    <?= $form->field($info,'OutFirstCost1',$shipping_templates)->textInput(['placeholder' => '--USD--']); ?>
    <?= $form->field($info,'OutSuccessorCost1',$shipping_templates)->textInput(['placeholder' => '--USD--']); ?>
        <?=
        $form->field($info,'OutshippingMethod2',$shipping_templates)->dropDownList($outShippingService,
            [
                'class' => 'col-lg-6',
                'prompt'=>'--境外物流选择--',
            ]
        ); ?>
    <?= $form->field($info,'OutFirstCost2',$shipping_templates)->textInput(['placeholder' => '--USD--']); ?>
    <?= $form->field($info,'OutSuccessorCost2',$shipping_templates)->textInput(['placeholder' => '--USD--']); ?>
    </div>
</div>
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
    }
</style>

<?php
$js  = <<< JS
//如果图片地址不改变就直接用原始数据
    function allImags() {
        var images = new Array();
        $('.extra-images').each(function() {
        images.push($(this).val());
        });
        $('#oatemplates-extrapage').val(JSON.stringify({'images':images}));
    }
    allImags();


// 生成图片序列

function serialize() {
    i=0;
    $(".serial").each(function() {
        i++;
        $(this).text("#" + i);
    });
}

//增加图片
function addImages() {
       
  total = 0;//判断当前图片数量
  $(".serial").each(function() {
        total++;
    });

    if(total<12){
        row = '<div class="form-group all-images">' +
    '<label class="col-lg-1"></label>' +
    '<strong class="serial">#</strong>'+
    '<div class="col-lg-3"><input type="text" class="form-control extra-images" ></div>'+
    '<div class="col-lg=1">'+
    '<button class="btn add-images">增加</button> '+
    '<button class="btn btn-error remove-image">删除</button> '+
    '<button class="btn up-btn btn-error">上移动</button> '+
    '<button class="btn down-btn btn-error">下移动</button> '+
    '<a target="_blank" href="">'+
    '<img src="" width="50" height="50">'+
    '</a>'+
    '</div>'+
    '</div>';
        $('.images').append(row);
        //重新计算序列
        serialize();
    }
}

//增加属性的按钮

$('.add-specifics').on('click',function() {
    var key = '<tr><th><input type="text" name="specficsKey"></th>';
    var value = '<td><input type="text" size="40" name="specficsValue"> ';
    var delBtn = '<input type="button" value="删除" onclick="$(this.parentNode.parentNode).remove()"></td></tr>';
    $('.specifics-tab').append(key + value + delBtn);
    return false;
});


// 初始化属性JSON
    function allSpecifics() {
        textarea = $('#oatemplates-specifics');
        var specifics = [];
        $('.specifics-tab').find('input[size="40"]').each(function() {
            var key = $(this).parents('tr').find('th').text();
            var value = $(this).val();
            if(key == ''){
                var key = $(this).parents('tr').find('th').find('input').val();
            }
            specifics.push([key,value]);
        });
        textarea.text(JSON.stringify({sepecifices:specifics}));
    }
allSpecifics();

// 属性变化事件,及时刷新JSON事件。
$('.specifics-tab').on('change','input[size="40"]',function() {
    allSpecifics();
});
//绑定上移事件
$('body').on('click','.up-btn',function() {
    var point = $(this).closest('div .form-group').find('strong').text().replace('#','');
    alert(point);
    if(point > 1){
        var temp = $(this).closest('div .all-images').clone(true);
        $(this).closest('div .all-images').prev().before(temp);
        $(this).closest('div .all-images').remove();
        serialize();
        //重新生成JSON
        var images = new Array();
        $('.extra-images').each(function() {
        images.push($(this).val());
        });
        $('#oatemplates-extrapage').val(JSON.stringify({'images':images}));
    }
    return false;
});

//绑定下移事件
$('body').on('click','.down-btn',function() {
    var point = $(this).closest('div .form-group').find('strong').text().replace('#','');
    alert(point);
    if(point < 12){
        var temp = $(this).closest('div .all-images').clone(true);
        $(this).closest('div .all-images').next().after(temp);
        $(this).closest('div .all-images').remove();
        serialize();
        //重新生成JSON
        var images = new Array();
        $('.extra-images').each(function() {
        images.push($(this).val());
        });
        $('#oatemplates-extrapage').val(JSON.stringify({'images':images}));
    }
    return false;
});


//绑定增加按钮事件
$('body').on('click','.add-images',function() {
    addImages();
    return false;
    });
//删除附加图
$('body').on('click','.remove-image',function() {
    $(this).closest('div .form-group').remove();
    allImags();//重新生成JSON
    serialize();//重新生成序列
        
        
});


//实时刷新图片
$('body').on('click','.extra-images',function() {
    new_image = $(this).val();
    $(this).parents('div .form-group').find('a').attr('href',new_image);
    $(this).parents('div .form-group').find('img').attr('src',new_image);
});

//绑定事件, 实时封装JSON数据
$('body').on('click','.all-images',function() {
    var text = '';
    var images = new Array();
    $('.extra-images').each(function() {
        images.push($(this).val());
    });
    $('#oatemplates-extrapage').val(JSON.stringify({'images':images}));
});


// 多属性设置模态框
$(".var-btn").click(function() {
    $('.modal-body').children('div').remove(); //清空数据
    $.get('{$templatesVarUrl}',{id:{$info->nid}},
        function(data) {
            $('.modal-body').html(data);
        }
    );
});


//保存按钮
$('#save-only').on('click',function() {
    $.ajax({
        url:'/channel/ebay-save',
        type:'post',
        data:$('#msg-form').serialize(),
        success:function(ret) {
            alert(ret);
        }
    });
})
JS;
$this->registerJs($js);
?>


<style>
    @media (min-width: 768px) {
        .modal-xl {
            width: 70%;
            /*max-width:1200px;*/
        }
    }
</style>
