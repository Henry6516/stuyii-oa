<?php

use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierOrder */

$this->title = '采购单明细';
$form = ActiveForm::begin();
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
                     return Html::a('<span  class="glyphicon glyphicon-trash"></span>','', $options);
                 },
                 'width' => '60px'
             ],
         ],

         'attributes' => [

             'sku' => ['type' => TabularForm::INPUT_TEXT,
                 'options' => ['class' => 'sku'],
             ],
             'image' => [ 'type' => TabularForm::INPUT_TEXT,
                 'options' => ['class' => 'image'],
             ],
             'goodsName' => [ 'type' => TabularForm::INPUT_TEXT,
                 'options' => ['class' => 'goodsName'],
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
             'purchaseNumber' => [ 'type' => TabularForm::INPUT_TEXT,
                 'options' => ['class' => 'purchaseNumber'],
             ],
             'purchasePrice' => [ 'type' => TabularForm::INPUT_TEXT,
                 'options' => ['class' => 'purchasePrice'],
             ],
             'deliveryNumber' => [ 'type' => TabularForm::INPUT_TEXT,
                 'options' => ['class' => 'deliveryNumber'],
             ],
         ]
     ]);
 }
 catch (Exception $why) {
     throw new Exception($why);
}
