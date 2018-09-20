<?php

use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierOrder */

$this->title = '采购单明细';

$form = ActiveForm::begin([
    'id' => 'detail-form'
]);
try {
    echo TabularForm::widget([
        'dataProvider' => $dataProvider,
        'id' => 'detail-table',
        'form' => $form,
        'actionColumn' => [
            'class' => '\kartik\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model, $key) {
//                    $delete_url = Url::to(['/goodssku/delete', 'id' => $key]);
                    $options = [
                        'title' => '删除',
                        'aria-label' => '删除',
                        'data-id' => $key,
                    ];
                    return Html::a('<span  class="glyphicon glyphicon-trash"></span>', '', $options);
                },
                'width' => '60px'
            ],
        ],

        'attributes' => [
            'sku' => ['type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'sku', 'readonly' => true],
            ],
            'billNumber' => ['type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'billNumber', 'readonly' => true],
                'columnOptions' => ['width' => '190px'],
                'value' => 'billNumber',
            ],
            'image' => ['type' => TabularForm::INPUT_RAW,
                'options' => ['class' => 'image'],
                'id' => 'image-view',
                'value' => function ($model) {
                    return Html::a(Html::img($model->image, [
                        'alt' => '缩略图', 'width' => 50,
                    ]), $model->image, ['target' => '_blank', 'class' => 'image-view']
                    );
                }
            ],
            'supplierGoodsSku' => ['type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'supplierGoodsSku', 'readonly' => true],
            ],

            'goodsName' => ['type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'goodsName', 'readonly' => true],
            ],
            'property1' => ['type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'property1', 'readonly' => true],
            ],
            'property2' => ['type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'property2', 'readonly' => true],
            ],
            'property3' => ['type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'property3', 'readonly' => true],
            ],
            'purchaseNumber' => ['type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'purchaseNumber', 'readonly' => true],
            ],
            'purchasePrice' => ['type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'purchasePrice', 'readonly' => true],
            ],
            'deliveryAmt' => ['type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'deliveryAmt'],
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


$saveOrderDetailUrl = Url::toRoute('save-order-detail');
$js = <<< JS
/*
save order detail
 */
$('.save-detail').click(function() {
    //锁定保存按钮
    $(this).attr('disabled','true');
    //判断发货数量
    var purNum = shipNum = 0;
    $('.purchaseNumber').each(function(i,item) {
        purNum += parseInt($(item).val()); 
    });
    $('.deliveryAmt').each(function(i,item) {
      shipNum += parseInt($(item).val()); 
    });
    
    if(shipNum > purNum) {
        krajeeDialog.confirm('您的发货数量大于采购数量，确定要发货吗？', function (result) {
            if(result){
                $.ajax({
                    url: '$saveOrderDetailUrl',
                    data: $('#detail-form').serialize(),
                    type: 'POST',
                    success: function(res) {
                        alert(res);
                        window.location.reload(); 
                    }
                });
            }
        });
    }else{
        $.ajax({
            url: '$saveOrderDetailUrl',
            data: $('#detail-form').serialize(),
            type: 'POST',
            success: function(res) {
                alert(res);
                window.location.reload(); 
            }
        });
    }
})


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
