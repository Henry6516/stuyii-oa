<?php
/**
 * @desc PhpStorm.
 * @author: turpure
 * @since: 2018-04-02 17:12
 */
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\grid\GridView;
use kartik\builder\TabularForm;
use yii\helpers\Url;

$form = ActiveForm::begin([
    'id'=>'detail',
    ])
?>
<div class="bind">
<?php

try{
    echo TabularForm::widget([
        'dataProvider' => $dataProvider,
        'id' => 'detail-table',
        'form'=>$form,
        'actionColumn' => false,
        'checkboxColumn' => false,
        'attributes'=>[
            'childId'=>['label'=>'唯一编码', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'childId','readonly'=>true],
                'columnOptions'=>['width'=>'170px']
            ],
            'color'=>['label'=>'颜色', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'color','readonly'=>true],
            ],
            'proSize'=>['label'=>'尺码/型号', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'proSize','readonly'=>true],
            ],
            'pySku'=>['label'=>'普源SKU', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'pySku'],
            ],
            'varMainImage'=>['label'=>'图片', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'varMainImage','readonly'=>true],
            ],
            'image' =>[
                'label' =>'图片',
                'type' => TabularForm::INPUT_RAW,
                'options' => ['class' => 'image'],
                'value' => function($model){
                    return '<img width="50" height="50" src="'.$model->varMainImage.'">';
                }
            ]


        ],

        // configure other gridview settings
        'gridSettings'=>[
            'panel'=>[
                'type'=>GridView::TYPE_PRIMARY,
                'footer'=>false,
                'before' => '
                <div class="row">
                    <div class="col-sm-4">
                     <input type="text" value="'.$code.'"class="mine-code form-control" readonly="true">
                    </div>
                    <div class="col-sm-4">
                     <input type="text" class="py-code form-control" placeholder="--普源商品编码--">
                    </div>
                    <div class="col-sm-4">
                        <button id="save-detail" class="btn btn-success">保存</button>
                    </div>
                </div>
                '
            ]
        ]

    ]);
    ActiveForm::end();

}

catch (\Exception $why) {

}

?>
    <a href="#" id="back-to-top" title="Back to top">&uarr;</a>
</div>
<?php
$saveUrl = Url::toRoute(['save-bind-detail','mid'=>$mid]);
$js = <<< JS


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


//save 

$('#save-detail').on('click',function() {
    var code_input = $('.py-code');
    var code = code_input.val();
    if(code.length === 0){
        alert('请填写普源商品编码');
        return false;
    }
    var formData = $('form#detail').serialize();
    $.ajax({
        url:'$saveUrl' + '&code=' + code,
        type:'post',
        data:formData, 
        success:(function(ret) { 
            alert(ret);
        })
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
JS;
$this->registerJs($js);
?>

<style>
    .bind {
        text-align: center; /*让div内部文字居中*/
        background-color: #fff;
        border-radius: 20px;
        width: 67%;
        height: 50%;
        margin: auto;
        /*position: absolute;*/
        top: 0;
        left: 0;
        right: 0;
        bottom: 100px;
    }


    @media (min-width: 768px) {
        .modal-xl {
            width: 70%;
            /*max-width:1200px;*/
        }
    }

    .panel-primary > .panel-heading {
        color: black;
        background-color: whitesmoke;
        border-color: transparent;
    }
    .panel-primary {
        border-color: transparent;
    }

    .panel-footer {
        padding: 10px 15px;
        background-color: white;
        border-top: 1px solid #ddd;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
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
        color: #0593d3;
        cursor: pointer;
        border-radius: 5px;
        text-decoration: none;
        transition: opacity 0.2s ease-out;
        opacity: 0;
        border: 1px solid #0593d3;
    }
    #back-to-top:hover {
        background: #e9ebec;
    }
    #back-to-top.show {
        opacity: 1;
    }

   
</style>
