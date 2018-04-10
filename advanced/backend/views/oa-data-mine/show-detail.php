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
    'method'=>'post',])
?>

<?php

try{
    echo TabularForm::widget([
        'dataProvider' => $dataProvider,
        'id' => 'detail-table',
        'form'=>$form,
        'actionColumn'=>[
            'class' => '\kartik\grid\ActionColumn',
            'template' =>'{delete}',
            'buttons' => [
                'delete' => function ($url, $model, $key) {
                    $options = [
                        'title' => '删除',
                        'aria-label' => '删除',
                        'data-id' => $key,
                        'class' =>'delete-icon'
                    ];
                    return Html::a('<span  class="del-detail glyphicon glyphicon-trash"></span>','#', $options);
                },
                'width' => '30px'
            ],
        ],
        'attributes'=>[

            'childId'=>['label'=>'唯一编码', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'childId'],
                'columnOptions'=>['width'=>'170px']
            ],
            'color'=>['label'=>'颜色', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'color'],
            ],
            'proSize'=>['label'=>'尺码/型号', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'proSize'],
            ],
            'quantity'=>['label'=>'库存', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'quantity'],
            ],
            'price'=>['label'=>'价格', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'price'],
            ],
            'msrPrice'=>['label'=>'MSR价格', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'msrPrice'],
            ],
            'shipping'=>['label'=>'运费', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'shipping'],
            ],
            'shippingWeight'=>['label'=>'重量', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'shippingWeight'],
            ],
            'shippingTime'=>['label'=>'配送时长', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'shippingTime'],
            ],
            'varMainImage'=>['label'=>'图片', 'type'=>TabularForm::INPUT_TEXT,
                'options'=>['class'=>'varMainImage'],
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
                'after'=>
                    '
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="input-group">
                                 <input type="text"  class="x-row form-control" placeholder="--新增行数--">
                                    <span class="input-group-btn">
                                    <button class="btn btn-default" id="add-detail" type="button">确定</button>
                                     </span>
                            </div><!-- /input-group -->
                        </div><!-- /.col-lg-6 -->
                        <div class="col-sm-2">
                            <div class="input-group">
                                 <input type="text" class="inventory-replace form-control" placeholder="--库存设置--">
                                    <span class="input-group-btn">
                                    <button class="btn btn-default" id="inventory-set" type="button">确定</button>
                                     </span>
                            </div><!-- /input-group -->
                        </div>
                        <div class="col-sm-2">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button type="button" class="op-btn btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">=<span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a class="operation" href="#"><span style="font-size: 17px">=</span></a></li> 
                                    <li><a class="operation" href="#"><span style="font-size: 17px">+</span></a></li>
                                    <li><a class="operation" href="#"><span style="font-size: 17px">-</span></a></li>
                                    <li><a class="operation" href="#"<span style="font-size: 17px">*</span></a></li> 
                                    <li><a class="operation" href="#"<span style="font-size: 17px">/</span></a></li> 
                                 </ul>
                            </div><!-- /btn-group -->
                        <div class="input-group" >
                            <input type="text" class="price-replace form-control"   placeholder="--设置价格--">
                            <span class="input-group-btn">
                                <button id="price-set" class="btn btn-default" type="button">确定</button>
                            </span>
                        </div>
                    </div>
                </div> 
                        <div class="col-sm-2">
                            <div class="input-group">
                                 <input type="text" class="form-control shipping-replace" placeholder="--运费设置--">
                                    <span class="input-group-btn">
                                    <button class="btn btn-default" id="shipping-set" type="button">确定</button>
                                     </span>
                            </div><!-- /input-group -->
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                 <input type="text" class="msrp-replace form-control" placeholder="--建议零售价">
                                    <span class="input-group-btn">
                                    <button class="btn btn-default" id="msrp-set" type="button">确定</button>
                                     </span>
                            </div><!-- /input-group -->
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                 <input type="text" class="form-control shipping_time-replace" placeholder="--运输时间--">
                                    <span class="input-group-btn">
                                    <button class="btn btn-default" id="shipping_time-set" type="button">确定</button>
                                     </span>
                            </div><!-- /input-group -->
                        </div>
                       
                    </div>
                    <div class="row" style="margin-top: 1%">
                 <div class="col-sm-2 col-md-push-10">
                    <button class="btn btn-danger">删除行</button>
                    <button id="save-detail" class="btn btn-info">保存</button>
                  </div>
                  <div class="col-sm-2">
                    
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

<?php
$delDetailUrl = Url::toRoute(['delete-detail']);
$saveDetailUrl = Url::toRoute(['save-detail','mid'=>$mid]);

$js = <<< JS

/*
delete row
 */

$('table').on('click','.del-detail', function() {
    var id = $(this).parents('tr').attr('data-key');
    $(this).parents('tr').remove();
    if(id.indexOf('New')>=0){
        counter = counter -1;
    }
    else {
        $.ajax({
            url: '$delDetailUrl',
            type:'post',
            data:{id:id},
            success: function() {
            }
        });
        
    }
})

/*
add-row
 */
var counter = 1;
$('#add-detail').on('click',function() {
    var row_num = $('.x-row').val();
    row_num = row_num ? row_num :1;
    var table= $('table');
    var row = $('.kv-tabform-row:last')
    var key = row.attr('data-key');
    while(row_num>0){
        var new_key = 'New-' + counter; 
        var new_row = row.clone(true);
        new_row.children('td:first').text(new_key);
        new_row.attr('data-key',new_key);
        var rep = new RegExp(key,'g');
        new_row.html(new_row.html().replace(rep,new_key));
        table.append(new_row)   ;
        counter += 1;
        row_num -= 1;
    }
})

/*
batch setting
 */
//inventory-set
$('#inventory-set').on('click',function() {
    var inventory = $('.inventory-replace').val();
    $('.quantity').each(function() {
        $(this).val(inventory);
    })
})

//shipping-set
$('#shipping-set').on('click',function(){
    var shipping = $('.shipping-replace').val();
    $('.shipping').each(function() {
        $(this).val(shipping);
    })
})

//msrp-set
$('#msrp-set').on('click',function() {
    var msrp = $('.msrp-replace').val();
    $('.msrPrice').each(function() {
        $(this).val(msrp);
    })
})

//shipping_time-set

$("#shipping_time-set").on('click',function() {
    var time = $('.shipping_time-replace').val();
    $('.shippingTime').each(function() {
        $(this).val(time);
    })
})

/*
price setting
 */

//operator
$('.operation').on('click',function() {
    var op = $(this).text()
    var button = $('.op-btn');
    button.html(button.html().replace(button.html()[0],op));
})

//set price
$('#price-set').on('click',function() {
    var price_replace = $('.price-replace').val()?parseFloat($('.price-replace').val()):0;
    var op = $('.op-btn').html()[0]
    console.log(op);
    
    $('.price').each(function() {
        if(op ==='='){
            $(this).val(price_replace);
        }
    
        if(op === '+'){
            $(this).val(parseFloat($(this).val()) + price_replace);
        }
        
        if(op ==='-'){
            $(this).val(parseFloat($(this).val()) - price_replace);
        }
        
        if(op === '*'){
            $(this).val(parseFloat($(this).val()) * price_replace)
        }
        
        if(op === '/'){
            $(this).val(parseFloat($(this).val()) / price_replace)
        }
})
})

/*
save detail
 */
$('#save-detail').on('click',function() {
    var btn = $(this);
    btn.attr('disabled',true);
    var detail = $("#detail").serialize();
    $.ajax({
        type:'post',
        url:'$saveDetailUrl',    
        data:detail,
        success:function(ret) {
            alert(ret);
            btn.removeAttr('disabled');
            return false;
        }
    });
    return false;
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

   
</style>
