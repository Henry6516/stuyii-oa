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
                     return Html::a('<span  class="glyphicon glyphicon-trash"></span>','', $options);
                 },
                 'width' => '60px'
             ],
         ],

         'attributes' => [
             'sku' => ['type' => TabularForm::INPUT_TEXT,
                 'options' => ['class' => 'sku','readonly'=>true],
             ],
             'billNumber' => ['type' => TabularForm::INPUT_TEXT,
                 'options' => ['class' => 'billNumber', 'readonly' => true],
                 'value' => 'oa_supplierOrder.billNumber',
                 ],
             'image' => [ 'type' => TabularForm::INPUT_RAW,
                 'options' => ['class' => 'image'],
                 'value' => function($model) {
                     return Html::a(Html::img($model->image,[
                         'alt'=>'缩略图','width'=>50,
                     ]),$model->image,['target'=>'_blank']
                     );
                 }
             ],
             'supplierGoodsSku' => [ 'type' => TabularForm::INPUT_TEXT,
                 'options' => ['class' => 'supplierGoodsSku','readonly'=>true],
             ],

             'goodsName' => [ 'type' => TabularForm::INPUT_TEXT,
                 'options' => ['class' => 'goodsName','readonly'=>true],
             ],
             'property1' => [ 'type' => TabularForm::INPUT_TEXT,
                 'options' => ['class' => 'property1','readonly'=>true],
             ],
             'property2' => [ 'type' => TabularForm::INPUT_TEXT,
                 'options' => ['class' => 'property2','readonly'=>true],
             ],
             'property3' => [ 'type' => TabularForm::INPUT_TEXT,
                 'options' => ['class' => 'property3','readonly'=>true],
             ],
             'purchaseNumber' => [ 'type' => TabularForm::INPUT_TEXT,
                 'options' => ['class' => 'purchaseNumber','readonly'=>true],
             ],
             'purchasePrice' => [ 'type' => TabularForm::INPUT_TEXT,
                 'options' => ['class' => 'purchasePrice','readonly'=>true],
             ],
             'deliveryAmt' => [ 'type' => TabularForm::INPUT_TEXT,
                 'options' => ['class' => 'deliveryAmt'],
             ],
         ],
         'gridSettings'=>[
             'panel'=>[
                 'before'=>false,
                 'footer'=>false,
                 'after'=>
                     Html::button('保存', ['type'=>'button', 'class'=>' btn btn-primary save-detail'])
             ]
         ]
     ]);
 }
 catch (Exception $why) {
     throw new Exception($why);
}
ActiveForm::end();


$saveOrderDetailUrl = Url::toRoute('save-order-detail');
$js = <<< JS
/*
save order detail
 */
$('.save-detail').click(function() {
  $(this).attr('disabled','true');
  $.ajax({
    url: '$saveOrderDetailUrl',
    data: $('#detail-form').serialize(),
    type: 'POST',
    succes: function(res) {
      alert(res);
      window.location.reload(); 
    }
  });
})
JS;
 $this->registerJs($js);

?>

<style>
    .kv-panel-after {
        float: right;
    }
</style>
