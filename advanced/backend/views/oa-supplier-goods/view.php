<?php

use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use yii\helpers\Html;


$this->title = '供应商SKU';
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
//                    $delete_url = Url::to(['/goodssku/delete', 'id' => $key]);
                    $options = [
                        'title' => '删除',
                        'aria-label' => '删除',
                        'data-id' => $key,
                    ];
                    return Html::a('<span  class="glyphicon glyphicon-trash"></span>','', $options);
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
            'purchasPrice' => [ 'type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'purchasPrice'],
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
            'purchasNumber' => [ 'type' => TabularForm::INPUT_TEXT,
                'options' => ['class' => 'purchasNumber'],
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


?>

