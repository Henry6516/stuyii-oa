<?php

use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '供应商SKU';
$this->params['breadcrumbs'][] = ['label' => '产品列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$form = ActiveForm::begin([
        'id' => 'sku-form'
]);

try {
    echo TabularForm::widget([
        'dataProvider' => $dataProvider,
        'id' => 'sku-table',
        'form' => $form,
        'actionColumn' => [
            'class' => '\kartik\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model, $key) {
//
                    $options = [
                        'title' => '删除',
                        'aria-label' => '删除',
                        'data-id' => $key,
                        'class' => 'delete-sku'
                    ];
                    return Html::a('<span  class="glyphicon glyphicon-trash"></span>','#', $options);
                },
                'width' => '60px'
            ],
        ],
        'attributes' => [

            'sku' => ['type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'sku'],
            ],
            'property1' => [ 'type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'property1'],
            ],
            'property2' => [ 'type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'property2'],
            ],
            'property3' => [ 'type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'property3'],
            ],
            'costPrice' => [ 'type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'costPrice'],
            ],
            'purchasePrice' => [ 'type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'purchasePrice'],
            ],
            'weight' => [ 'type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'weight'],
            ],
            'image' => [ 'type' => TabularForm::INPUT_RAW,
                'options' => ['class' => 'image'],
                'value' => function($model) {
                    return Html::a(Html::img($model->image,[
                        'alt'=>'缩略图','width'=>50,
                    ]),$model->image,['target'=>'_blank', 'class' => 'image-view']
                    );
                }
            ],
            'lowestPrice' => [ 'type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'lowestPrice','readonly' => 'true'],
            ],
            'purchaseNumber' => [ 'type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'purchaseNumber','readonly' => 'true'],
            ],
            'supplierGoodsSku' => [ 'type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'supplierGoodsSku'],
            ],
        ],
        'gridSettings'=>[
            'panel'=>[
                'before'=>false,
                'footer'=>false,
                'after'=>
                    Html::button('保存', ['type'=>'button', 'class'=>'btn btn-primary save-sku'])
            ]
        ]
    ]);
}
catch (Exception $why) {
    throw new \Exception($why);
}
ActiveForm::end();

$deleteUrl = Url::toRoute(['delete-sku']);
$saveUrl = Url::toRoute(['save-sku']);

$js = <<< JS


/*
delete sku
 */
$('.delete-sku').click(function() {
  var id = $(this).attr('data-id');
  $("[data-key=" + id + "]").remove();
  $.get('$deleteUrl',{id:id},function(res) {
    alert(res);
    window.location.reload();
  })
})

/*
save sku
 */
$('.save-sku').click(function() {
  $.ajax({
    url: '$saveUrl',
    data: $('#sku-form').serialize(),
    type: 'POST',
    success:function(res) {
      alert(res);
      window.location.reload();
    }
  });
})

 
 
JS;

$this->registerJs($js);
?>


<style>
    .content-header {
        margin-bottom: 1%;
    }
   .pull-right {
    float: left !important;
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
        transform: scale(3, 3);
        transition: .3s transform;
    }

</style>