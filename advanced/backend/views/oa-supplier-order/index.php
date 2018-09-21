<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaSupplierOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '供应商订单';
$this->params['breadcrumbs'][] = $this->title;

$queryUrl = Url::toRoute(['query']);
$syncUrl = Url::toRoute(['sync']);
$payUrl = Url::toRoute(['pay']);
$exportDetailUrl = Url::toRoute(['export-detail']);
$inputExpressUrl = Url::toRoute(['input-express']);
$deliveryTemplateUrl = Url::toRoute(['delivery-template']);
$checkUrl = Url::toRoute(['check']);
?>


<div class="oa-supplier-order-index" style="margin-top: 1%;">
    <div class="input-file">
        <?php $form = ActiveForm::begin(['action' => 'input-delivery-order', 'options' => ['id' => 'upload', 'enctype' => 'multipart/form-data']]) ?>
        <?= $form->field($file, 'excelFile')->fileInput() ?>
        <?php ActiveForm::end() ?>
    </div>
    <div style="margin-top: 1%;margin-bottom: 1%">
        <div class="row">
            <div class="col-sm-1">
                <div class="btn-group">
                    <button type="button" class="btn  btn-default">批量操作</button>
                    <button type="button" class="btn lots-action btn-default dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <?php echo
                        "<li><a href='#' data-url=$syncUrl class='sync-lots lots'>同步普源数据</a></li>
                        <li><a href='#' data-url=$inputExpressUrl class='input-express-lots lots'>导入物流单号</a></li>
                        <li><a href='#' class='input-delievery-order import' >导入发货单</a></li>
                        <li><a href='#' data-url=$checkUrl class='check-lots lots'>审核单据</a></li>
                        <li><a href='#' data-url=$exportDetailUrl class='export-lots'>导出采购单明细</a></li>";
                        ?>
                    </ul>
                </div>
            </div>
            <div class="col-sm-1">
                <a type='button' href='#' class='btn btn-default delivery-template'>发货单模板</a>
            </div>
            <div class="col-sm-1">
                <?= '<a target="_blank" href=' . $queryUrl . ' type="button" class="btn btn-default">同步采购单</a>' ?>
            </div>
        </div>
    </div>
    <?php //Pjax::begin(['id' => 'order-table']) ?>
    <?php
    Modal::begin([
        'id' => 'express-modal',
        'header' => '<h4 class="modal-title">填写物流单号</h4>',
        'footer' => '<a href="#" class="mod-act btn btn-success">发货</a>',
    ]);
    Modal::end();
    ?>
    <?php
    Modal::begin([
        'id' => 'payment-modal',
        'header' => '<h4 class="modal-title">填写支付金额</h4>',
        'footer' => '<a href="#" class="mod-act btn btn-success">确定</a>',
    ]);
    Modal::end();
    ?>
    <?php try {
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'id' => 'supplier-order-view',
            //'pjax' => 'true',
            'options' => ['data-pjax' => 'order-table'],
            'showPageSummary' => true,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                ['class' => 'kartik\grid\CheckboxColumn'],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'dropdown' => true,
                    'dropdownButton' => [
                        'label' => '操作',
                        'class' => 'action btn btn-default',
                        'data-toggle' => 'dropdown',
                        'aria-haspopup' => 'true',
                        'aria-expanded' => 'false'

                    ],
                    'template' => '{view} {update} {sync} {pay} {payment} {delivery} {input-express} 
                {input-delivery-order} {check} {export-detail} ',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            return "<li><a  href='#' class='view-act jump' data-url=$url>订单明细</a></li>";
                        },
                        'update' => function ($url, $model) {
                            return "<li><a href='#' class='update-act jump' data-url=$url>编辑订单</a></li>";
                        },
                        'sync' => function ($url, $model, $key) {
                            return "<li><a href='#' data-url=$url class='sync-act act'>同步普源数据</a></li>";
                        },
                        'delivery' => function ($url) {
                            return "<li><a href='#' data-url=$url  class='delivery-act mod' data-toggle='modal' data-target='#express-modal'>发货</a></li>";
                        },
                        'pay' => function ($url) {
                            return "<li><a href='#' data-url=$url  class='payment-act payment-mod' data-toggle='modal' data-target='#payment-modal'>请求付款</a></li>";
                        },
                        'payment' => function ($url) {
                            return "<li><a href='#' data-url=$url  class='payment-act jump' >付款明细</a></li>";
                        },
                        'input-express' => function ($url) {
                            return "<li><a class='input-express act' data-url=$url href='#'>导入物流单号</a></li>";
                        },
                        'check' => function ($url) {
                            return "<li><a class='check act' href='#' data-url=$url>审核单据</a></li>";
                        },
                        'input-delivery-order' => function ($url) {
                            return "<li><a href='#' class='input-delivery-act import' data-url=$url>导入发货单</a></li>";
                        },
                        'export-detail' => function ($url) {
                            return "<li><a href='#' class='export-detail-act export' data-url=$url>导出采购单明细</a></li>";
                        },
                    ],
                ],
                [
                    'attribute' => 'billNumber',
                    'pageSummary' => 'pageSummary',
                ],
                [
                    'attribute' => 'supplierName',
                    'pageSummary' => false,
                ],
                [
                    'attribute' => 'billStatus',
                    'pageSummary' => false,
                ],
                [
                    'attribute' => 'purchaser',
                    'pageSummary' => false,
                ],
                [
                    'attribute' => 'syncTime',
                    'pageSummary' => false,
                ],
                [
                    'attribute' => 'totalNumber',
                    'pageSummary' => true,
                ],
                [
                    'attribute' => 'amt',
                    'pageSummary' => true,
                ],
                [
                    'attribute' => 'paymentAmt',
                    'pageSummary' => true,
                ],
                [
                    'attribute' => 'unpaidAmt',
                    'pageSummary' => true,
                ],
                [
                    'attribute' => 'deliveryStatus',
                    'pageSummary' => false,
                ],
                [
                    'attribute' => 'expressNumber',
                    'pageSummary' => false,
                ],
                [
                    'attribute' => 'paymentStatus',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a($model->paymentStatus, Url::to(['oa-supplier-order/payment', 'id' => $model->id]),['target' => '_blank']);
                    }
                ],
            ],
        ]);
    } catch (Exception  $why) {
        throw new \Exception($why);
    }
    ?>

    <?php //Pjax::end() ?>

</div>

<?php


$js = <<< JS

function init() {
  //action
      $('.act').click(function() {
          var url = $(this).data('url');
          $.ajax({
            url:url,
            type:'get',
            success:function(res) {
                alert(res);
                $.pjax.reload({container:"#order-table",timeout: 5000});
            }
          });
      });
      
       $('.jump').click(function() {
            window.open($(this).data('url'));
      })
      
       $('.export').click(function() {
            window.location.href =$(this).data('url');
      });
      $('.import').click(function() {
            $('#uploadfile-excelfile').click();
      })
      $('#uploadfile-excelfile').change(function() {
        var that = this;
        var value = $(that).val();
        if(value.length === 0) {
          return false;
        }
        var form = $('#upload')[0];
        
        var formData = new FormData(form);
        $.ajax({
          url:'input-delivery-order',
          type:'post',
          data:formData,
          processData:false,
          contentType:false,
          success:function(res) {
            alert(res);
            $(that).val('');
          }
        });
      });
      
      //modal
      $('.mod').click(function() {
        var that = $(this);
        var html = '<textarea placeholder="多个单号请换行" class="express-text">';
        $('.modal-body').html(html);
        $('.modal-footer').find('a').attr('data-url',that.data('url'));                                          
      });   
      
      //modal-action
      $('.mod-act').click(function() {
        $(this).parents('.modal').find ('.close').click(); 
        var url = $(this).data('url');
        var data = $(this).parents('.modal').find('textarea').val();
        if(data.length == 0){
            alert('内容不能为空！');
            return false;
        }
        //debugger;
        $.ajax({
        url:url,
        type:'post',
        data:{number:data},
        success:function(res) {
          alert(res);
          $.pjax.reload({container:"#order-table",timeout: 5000});
        }
      });
      });
      
      /* payment mod */
      $('.payment-mod').click(function() {
        var that = $(this);
        
        var html = '<textarea placeholder="" class="payment-text">'
        $('.modal-body').html(html);
        $('.modal-footer').find('a').attr('data-url',that.data('url'));
      });
      
      /*act lots*/
      $('.lots').click(function() {
        var keys = $('#supplier-order-view').yiiGridView('getSelectedRows');
        if(keys.length === 0) {
          $('.lots-action').click();
          alert('请选择要操作的订单！');
          return false
         }
         var url = $(this).data('url');
         $.ajax({
          url: url,
          type:'POST',
          data:{id:keys},
          success:function(ret) {
            alert(ret);
            $.pjax.reload({container:"#order-table",timeout: 5000});
          }
         });
      });   
      
      /* export lots */
      $('.export-lots').click(function() {
        var keys = $('#supplier-order-view').yiiGridView('getSelectedRows');
        if(keys.length === 0) {
          $('.lots-action').click();
          alert('请选择要操作的订单！');
          return false
         }
         var url = $(this).data('url');
         url = url  + '?id=' + keys.join(',');
         window.location.href= url; 
      });
      
      /* export delivery templates */
      $('.delivery-template').click(function() {
        window.location.href="$deliveryTemplateUrl";
      });
}
$(document).ready(init());
$(document).on('pjax:complete',function() {
 init(); 
})

JS;
$this->registerJs($js);
?>
<style>
    .open { /* Display the dropdown on hover */
        position: absolute;
    }

    .open > button {
        position: relative;
        bottom: 5px;
    }

    textarea {
        margin: 0px;
        width: 567px;
        height: 180px;
    }

    .input-file {
        display: none !important;
    }

</style>



