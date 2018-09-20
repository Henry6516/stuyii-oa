<?php

use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierOrder */

$this->title = '付款明细';
?>

<div class="input-file" style="display: none">
    <?php $form = ActiveForm::begin(['action' => 'input-delivery-order', 'options' => ['id' => 'upload', 'enctype' => 'multipart/form-data']]) ?>
    <?= $form->field($file, 'excelFile', ['options' => ['data-id' => 0]])->fileInput() ?>
    <?php ActiveForm::end() ?>
</div>

<?php
$form = ActiveForm::begin([
    'id' => 'payment-form'
]);

try {
    echo TabularForm::widget([
        'dataProvider' => $dataProvider,
        'id' => 'detail-table',
        'form' => $form,
        'actionColumn' => [
            'class' => '\kartik\grid\ActionColumn',
            'template' => '{upload} {delete}',
            'buttons' => [
                'upload' => function ($url, $model, $key) {
                    $options = [
                        'title' => '上传凭证',
                        'aria-label' => '上传凭证',
                        'data-id' => $key,
                        'class' => 'image-upload',
                    ];
                    return Html::a('<span  class="glyphicon glyphicon-upload"></span>', '#', $options);
                },
                'delete' => function ($url, $model, $key) {
//                    $delete_url = Url::to(['/goodssku/delete', 'id' => $key]);
                    $options = [
                        'title' => '删除',
                        'aria-label' => '删除',
                        'data-id' => $key,
                    ];
                    return Html::a('<span  class="glyphicon glyphicon-trash"></span>', '#', $options);
                },
                'width' => '100px',
            ],
        ],

        'attributes' => [
            'id' => [
                'label' => '',
                'type' => TabularForm::INPUT_HIDDEN,
                'options' => ['class' => 'billNumber', 'readonly' => true],
            ],
            'billNumber' => ['type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'billNumber', 'readonly' => true],
                'columnOptions' => ['width' => '190px'],
                'value' => 'billNumber',
            ],
            'requestTime' => ['type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'requestTime', 'readonly' => true],
            ],
            'requestAmt' => ['type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'requestAmt', 'readonly' => true],
            ],
            'paymentStatus' => ['type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'paymentStatus', 'readonly' => true],
            ],

            'image' => [
                'label' => '凭证',
                'options' => ['class' => 'image', 'style' => 'width:100px'],
                'type' => TabularForm::INPUT_RAW,
                'id' => 'image-view',
                'value' => function ($model) {
                    return $model->img ?
                        Html::a(Html::img($model->img, ['alt' => '缩略图', 'width' => 50]), $model->img, ['target' => '_blank', 'class' => 'image-view view-' . $model->id]) :
                        Html::a(Html::img(Url::to("@web/img/noImg.jpg"), ['alt' => '缩略图', 'width' => 50]), '#', ['class' => 'image-view view-' . $model->id]);
                }
            ],
            'img' => [
                'label' => '',
                'type' => TabularForm::INPUT_HIDDEN,
                'options' => ['class' => 'img', 'readonly' => true],
            ],
            'paymentTime' => ['type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'paymentTime', 'readonly' => true],
            ],
            'comment' => ['type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'comment'],
            ],
            'paymentAmt' => ['type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'paymentAmt'],
            ],
        ],
        'gridSettings' => [
            'panel' => [
                'before' => false,
                'footer' => false,
                'after' =>
                    Html::button('保存', ['type' => 'button', 'class' => ' btn btn-primary save-detail'])
            ]
        ]
    ]);
} catch (Exception $why) {
    throw new Exception($why);
}
ActiveForm::end();

$savePaymentUrl = Url::toRoute('save-payment');
$imgUploadUrl = Url::toRoute('upload');
$js = <<< JS

/*
save order detail
 */
$('.save-detail').click(function() {
    //锁定保存按钮
    $(this).attr('disabled','true');
    krajeeDialog.confirm('确定付款？', function (result) {
        if(result){
            $.ajax({
                url: '$savePaymentUrl',
                data: $('#payment-form').serialize(),
                type: 'POST',
                success: function(res) {
                    alert(res);
                    window.location.reload(); 
                }
            });
        }
    });
    
})
//凭证上传
$('.image-upload').click(function() {
    $('#uploadfile-excelfile').data('id',$(this).data('id'));
    $('#uploadfile-excelfile').click();
})
$('#uploadfile-excelfile').change(function() {
    var id = $(this).data('id');
    var that = this;
    var value = $(that).val();
    if(value.length === 0) {
        return false;
    }
    var form = $('#upload')[0];
    var formData = new FormData(form);
    $.ajax({
        url:'{$imgUploadUrl}',
        type:'post',
        dataType: 'json',
        data:formData,
        processData:false,
        contentType:false,
        success:function(res) {
            alert(res.msg);
            if(res.code == 200){
                var img = '<img src="'+res.url+'" width=50>'
                $('.view-'+id).html(img);
                $('.view-'+id).attr('href',res.url);
                $('#oasupplierorderpaymentdetail-'+id+'-img').val(res.url);
            }else{
                
            }
        }
    });
});

JS;
$this->registerJs($js);
?>

<style>
    .kv-panel-after {
        float: right;
    }

    .image-view img {
        /*width: 250px;*/
        transition: .1s transform;
        transform: translateZ(0);
    }

    .image-view a:hover {
        width: 250px;
        overflow: visible;
    }

    .image-view img:hover {
        position: absolute;
        z-index: 1000;
        transform: scale(4, 4);
        transition: .3s transform;
    }
</style>
