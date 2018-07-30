<?php

use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '供应商SKU';
$this->params['breadcrumbs'][] = ['label' => '产品列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$form = ActiveForm::begin();

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
            'image' => [ 'type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'image'],
            ],
            'lowestPrice' => [ 'type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'lowestPrice'],
            ],
            'purchaseNumber' => [ 'type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'purchaseNumber'],
            ],
            'supplierGoodsSku' => [ 'type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'supplierGoodsSku'],
            ],
        ],
    ]);
}
catch (Exception $why) {
    throw new \Exception($why);
}


$deleteUrl = Url::toRoute(['delete-sku']);

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


JS;

$this->registerJs($js);
?>

