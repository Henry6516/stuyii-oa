<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$this->title = '数据详情';


Modal::begin([
    'id' => 'detail-modal',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
    'size' => "modal-xl"
]);
//echo
Modal::end();

?>
<!--<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">-->
<!--<script src="https://unpkg.com/vue/dist/vue.js"></script>-->
<!--<script src="https://unpkg.com/element-ui/lib/index.js"></script>-->
<!--<script src="https://cdn.bootcss.com/vue-resource/1.5.0/vue-resource.min.js"></script>-->

    <button class="btn btn-info save-btn">保存当前数据 </button>
    <button class="btn btn-danger save-complete-btn">保存并完善 </button>
    <button class="btn  btn-success export-btn">导出Joom模板 </button>

<?php $form = ActiveForm::begin([
    'id' => 'detail-form',
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'class' => 'radius-input',
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
<?= $form->field($mine, 'cat')->dropDownList(array_combine($cat,$cat), ['class'=>'cat-list','prompt' => '--请选择主类目--']) ?>
<?= $form->field($mine, 'subCat')->dropDownList($subCat, ['class'=>'sub-list','prompt' => '--请选择子类目--',]) ?>
<?= $form->field($mine,'description')->textarea(['style' => "width: 885px; height: 282px;"])?>

<div class="blockTitle" >
    <span>图片信息</span>
</div>
<div class="all-img">
<?php
echo '<div class="form-group field-oadataminedetail-mainimage has-success">
<label class="control-label col-md-2" for="oadataminedetail-mainimage">主图</label>
<div class="col-lg-8">
    <div class="col-md-4">
        <input type="text" id="oadataminedetail-mainimage" class="main-image form-control" name="OaDataMineDetail[MainImage]" value="'.$mine->MainImage.'" style="margin-top:2%; aria-invalid="false">
     </div>
     <div class="col-md-2">
        <img width="50" height="50" src="'.$mine->MainImage.'">
     </div>
</div>
<div class="col-lg-8"><div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div></div>
</div>'
;
for($i=0;$i<=10;$i++){
    $extra_image = 'extra_image'.(string)$i;
    $label_name = '附加图#'.(string)($i+1);
    echo '
    <div class="form-group field-oadataminedetail-extra_image0 has-success">
    <label class="control-label col-md-2" for="oadataminedetail-extra_image0">'.$label_name.'</label>
    <div class="col-lg-8">
        <div class="col-md-4"><input type="text"  class="extra-img form-control"  value="'.$mine->$extra_image.'" style="margin-top:2%;" aria-invalid="false"></div>
         <div class="col-md-4">
         <button class="btn add-img">增加</button>
         <button class="btn del-img">删除</button>
         <button class="btn up-img">上移</button>
         <button class="btn down-img">下移</button>
        <img width="50" height="50" src="'.$mine->$extra_image.'">
     </div>
    </div>
    <div class="col-lg-8"><div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div></div>
    </div>
    ';

}
?>
</div>

<div class="blockTitle" >
    <span>多属性信息</span>
</div>
<a data-toggle="modal" data-target="#detail-modal" class=" var-btn btn btn-default ">设置多属性</a>
<a href="#" id="back-to-top" title="Back to top">&uarr;</a>
<div class="blockTitle" >
<button class="btn btn-info save-btn">保存当前数据 </button>
    <button class="btn btn-danger save-complete-btn">保存并完善 </button>
<button class="btn  btn-success export-btn">导出Joom模板 </button>
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
        margin-bottom: 2%;
        margin-top: 2%;
    }

    .blockTitle span {
        margin-top: 20px;
        font-weight: bold;
    }

    #detail-form input {
        border-radius: 10px;                /* 圆角边框 */
    }

    #detail-form textarea {
        border-radius: 10px;                /* 圆角边框 */
    }

    .thumbnail:hover {
        border: 2px solid #00a65a;
    }

    .el-table__body input {
        border-radius: 15px ;
    }
    .el-table__body select {
        border-radius: 15px ;
    }


    #back-to-top {
        position: fixed;
        bottom: 40px;
        right: 40px;
        z-index: 9999;
        width: 40px;
        height: 40px;
        text-align: center;
        line-height: 30px;
        background: #f5f5f5;
        color: #444;
        cursor: pointer;
        border: 0;
        border-radius: 5px;
        text-decoration: none;
        transition: opacity 0.2s ease-out;
        opacity: 0;
    }
    #back-to-top:hover {
        background: #e9ebec;
    }
    #back-to-top.show {
        opacity: 1;
    }


    @media (min-width: 1000px) {
        .modal-xl {
            width: 80%;
            /*max-width:1200px;*/
        }
    }

</style>


<?php

$exportUrl = Url::toRoute(['export', 'mid' => $mid ]);
$saveUrl = Url::toRoute(['save-basic', 'mid' => $mid ]);
$saveCompleteUrl = Url::toRoute(['save-basic', 'mid' => $mid,'flag' => 'complete' ]);
$detailUrl = Url::toRoute(['detail','mid' => $mid]);
$subCatUrl = Url::toRoute(['sub-cat']);

$js = <<<JS

/*
back to top
 */

if ($('#back-to-top').length) {
    var scrollTrigger = 100, // px
        backToTop = function () {
            var scrollTop = $(window).scrollTop();
            if (scrollTop > scrollTrigger) {
                $('#back-to-top').addClass('show');
            } else {
                $('#back-to-top').removeClass('show');
            }
        };
    backToTop();
    $(window).on('scroll', function () {
        backToTop();
    });
    $('#back-to-top').on('click', function (e) {
        e.preventDefault();
        $('html,body').animate({
            scrollTop: 0
        }, 700);
    });
}

/*
export to csv
 */
$('.export-btn').on('click',function() {
    window.location = '$exportUrl';
    return false;
})

/*
save data
 */
$('#save-btn').on('click',function() {
    var tableData = $('#table-data').text();
    console.log(tableData);
    var formData = $('form#detail-form').serializeObject();
    $.ajax({
        url:'$saveUrl',
        type:'post',
        data:{'tableData':tableData, 'formData':formData},
        success:function(res) {
            alert(res);
        }
    });
})


$.prototype.serializeObject = function() {  
    var a, o, h, i, e;  
    a = this.serializeArray();  
    o = {};  
    h = o.hasOwnProperty;  
    for (i = 0; i < a.length; i++) {  
        e = a[i];  
        if (!h.call(o, e.name)) {  
            var key = e.name.replace('OaDataMineDetail[','').replace(']','')
            o[key] = e.value;  
        }  
    }  
    return o;
};   


/*
listen to image
 */
$('.main-image').on('change',function() {
    var new_image =  $(this).val();
    $(this).parents('div .form-group').find('img').attr('src', new_image);
})


/*
operate images
 */

//del
$('.del-img').on('click', function() {
    $(this).closest('div .form-group').remove();
});

//add
$('body').on('click','.add-img',function() {
    //be able to add?
    var image_num = $('.extra-img').length;
    if(image_num>=11){
        alert("已经达到图片个数上限！");
        return false ;
    }
    var element = '<div class="form-group field-oadataminedetail-extra_image7 has-success">\
    <label class="control-label col-md-2" for="oadataminedetail-extra_image0">附加图#</label>\
    <div class="col-lg-8">\
        <div class="col-md-4"><input type="text"  class="extra-img form-control"  value="" style="margin-top:2%;" aria-invalid="false"></div>\
         <div class="col-md-4">\
         <button class="btn add-img">增加</button>\
         <button class="btn del-img">删除</button>\
         <button class="btn up-img">上移</button>\
         <button class="btn down-img">下移</button>\
        <img width="50" height="50" src="">\
     </div>\
    </div>\
    <div class="col-lg-8"><div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div></div>\
    </div>';
    
    $('.all-img').append(element);
    listenImage();
    return false;
})

//change

function listenImage() {
    $('.extra-img').each(function() {
    var ele = $(this);
    ele.change(function() {
        var new_image = $(this).val();
        $(this).parents('div .form-group').find('img').attr('src',new_image);
    })
})
  
}

//move-up

$('body').on('click','.up-img',function() {
    var position = $('.up-img').index(this);
    if(position === 0){
        alert("已经在最上面了！")
            return false;
    }
    var images = [];
    $('.extra-img').each(function(index) {
        if(index === position-1 || index === position ){
            images.push($(this).val())    
        }
            
    })
   
    $('.extra-img').each(function(index) {
        if(index === position-1){
            $(this).val(images[1]);
            $(this).parents('div .form-group').find('img').attr('src',images[1])
        }
        if(index === position){
            $(this).val(images[0]);
            $(this).parents('div .form-group').find('img').attr('src',images[0])
        }
            
    })
    
    return false;
  
})

//move-down
$('body').on('click','.down-img',function() {
    var position = $('.down-img').index(this);
    var image_num = $('.extra-img').length;
    if(position + 1 === image_num){
        alert("已经在最下面了！")
            return false;
    }
    var images = [];
    $('.extra-img').each(function(index) {
        if(index === position || index === position +1 ){
            images.push($(this).val())    
        }
            
    })
   
    $('.extra-img').each(function(index) {
        if(index === position){
            $(this).val(images[1]);
            $(this).parents('div .form-group').find('img').attr('src',images[1])
        }
        if(index === position + 1){
            $(this).val(images[0]);
            $(this).parents('div .form-group').find('img').attr('src',images[0])
        }
            
    })
    
    return false;
  
})

/*
save data
 */

$('.save-btn').on('click',function() {
    var images = {};
    $('.extra-img').each(function(index) {
        images['extra_image' + index] = $(this).val();
    });
    var form_data = $('form#detail-form').serializeObject();
    // var image_data = JOSN.stringify(images);
    $.ajax({
        url:'$saveUrl',
        type:'post',
        data:{'form': form_data, 'images':images},
        success:(function(ret) { 
            alert(ret);
        })
    });
    return false;
})

/*
save-complete
 */
 
$('.save-complete-btn').on('click',function() {
    var images = {};
    $('.extra-img').each(function(index) {
        images['extra_image' + index] = $(this).val();
    });
    var form_data = $('form#detail-form').serializeObject();
    // var image_data = JOSN.stringify(images);
    $.ajax({
        url:'$saveCompleteUrl',
        type:'post',
        data:{'form': form_data, 'images':images},
        success:(function(ret) { 
            alert(ret);
        })
    });
    return false;
})

// 多属性设置模态框
$(".var-btn").click(function() {
    $('.modal-body').children('*').remove(); //清空数据
    $.get('$detailUrl',
        function(data) {
            $('.modal-body').html(data);
        }
    );
});


$.prototype.serializeObject = function() {  
    var a, o, h, i, e;  
    a = this.serializeArray();  
    o = {};  
    h = o.hasOwnProperty;  
    for (i = 0; i < a.length; i++) {  
        e = a[i];  
        if (!h.call(o, e.name)) {  
            var key = e.name.replace('OaDataMineDetail[','').replace(']','')
            o[key] = e.value;  
        }  
    }  
    return o;
};


/*
two-level select
 */
$('.cat-list').on('change',function(){
    var cat =$('.cat-list option:selected').val();
    $('.sub-list option').not(':first').remove();
    $.get("$subCatUrl",{cat:cat},function(cats) {
        var select = $('.sub-list');
        $.each(JSON.parse(cats), function(index,value) {
            var html = '<option value="'+ value +'">'+ value +'</option>';
            select.append(html);
        })
    })
})

//default cat
$("option[value={$mine->cat}]").attr("selected",true);

$("option:contains({$mine->subCat})").attr("selected",true);
JS;


$this->registerJs($js);
?>
