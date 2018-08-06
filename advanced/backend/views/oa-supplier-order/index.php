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
?>
<?php
Modal::begin([
    'id' => 'express-modal',
    'header' => '<h4 class="modal-title">填写物流单号</h4>',
    'footer' =>  '<a href="#" class="mod-act btn btn-success">发货</a>',
]);

Modal::end();
?>
<div class="oa-supplier-order-index" style="margin-top: 1%;">
    <div class="input-file">
        <?php $form = ActiveForm::begin(['action'=>'input-delivery-order','options' => ['id' => 'upload','enctype' => 'multipart/form-data']]) ?>
        <?= $form->field($file, 'excelFile')->fileInput() ?>
        <?php ActiveForm::end() ?>
    </div>
    <div style="margin-top: 1%;margin-bottom: 1%">

<!--        <div class="btn-group">-->
<!--            <button type="button" class="btn  btn-info">批量操作</button>-->
<!--            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                <span class="caret"></span>-->
<!--                <span class="sr-only">Toggle Dropdown</span>-->
<!--            </button>-->
<!--            <ul class="dropdown-menu">-->
<!--                <li><a href="#">Action</a></li>-->
<!--                <li><a href="#">Another action</a></li>-->
<!--                <li><a href="#">Something else here</a></li>-->
<!--                <li role="separator" class="divider"></li>-->
<!--            </ul>-->
<!--        </div>-->
        <?= '<a target="_blank" href='. $queryUrl. ' type="button" class="btn btn-danger">同步采购单</a>' ?>

    </div>
    <?php Pjax::begin(['id' => 'order-table']) ?>

    <?php try {echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => 'true',
        'options'=>['data-pjax'=>'order-table'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
                'template' => '{view} {update} {sync} {pay} {delivery} {input-express} 
                {input-delivery-order} {check} {export-detail} ',
                'buttons' => [
                    'view' => function ($url,$model,$key) {
                        return "<li><a  href='#' class='view-act jump' data-url=$url>订单明细</a></li>";
                    },
                    'update' => function ($url, $model) {
                        return "<li><a href='#' class='update-act jump' data-url=$url>编辑订单</a></li>";
                    },
                    'sync' => function ($url,$model,$key) {
                        return "<li><a href='#' data-url=$url class='sync-act act'>同步普源数据</a></li>";
                    },
                    'delivery' => function ($url) {
                        return "<li><a href='#' data-url=$url  class='delivery-act mod' data-toggle='modal' data-target='#express-modal'>发货</a></li>";
                    },
                    'pay' => function ($url) {
                        return "<li><a  class='pay-act act' href='#' data-url=$url>付款</a></li>";
                    },
                    'input-express' => function ($url) {
                        return "<li><a class='input-express act' data-url=$url href='#'>导入物流单号</a></li>";
                    },
                    'check' => function () {
                        return '<li><a href="#">审核单据</a></li>';
                    },
                    'input-delivery-order' => function ($url) {
                        return "<li><a href='#' class='input-delivery-act import' data-url=$url>导入发货单</a></li>";
                    },
                    'export-detail' => function ($url) {
                        return "<li><a href='#' class='export-detail-act export' data-url=$url>导出采购单明细</a></li>";
                    },
                ],
            ],
            'billNumber',
            'supplierName',
            'goodsName',
            'billStatus',
            'purchaser',
            'syncTime',
            'totalNumber',
            'amt',
            'deliveryStatus',
            'expressNumber',
            'paymentStatus',
        ],
    ]);}
    catch (Exception  $why) {
        throw new \Exception($why);
    }
     ?>

    <?php Pjax::end()?>

</div>

<?php

$js =  <<< JS

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
          }
        });
      });
      
      // $('.import').click(function() {
      //   var input = document.getElementById('delivery-input')
      //   var url = $(this).data('url');
      //   debugger;
      //   input.click();
      //   input.addEventListener('change',function() {
      //     if(!input.value) {
      //       return; 
      //     }
      //     var file = input.files[0];
      //     var reader = new FileReader();
      //     reader.onload = function (ev) { 
      //       var data = ev.target.result;  
      //       $.ajax({
      //         url:url,
      //         type: 'post',
      //         contentType: 'application/octet-stream', 
      //         data:{data:data},
      //         processData: false,                                        
      //         success: function(res) {
      //           alert(res);
      //         }
      //       });
      //      }
      //      reader.readAsBinaryString( file);
      //   })
      //  
      // })
      
      
      //modal
      $('.mod').click(function() {
        var that = $(this);
        var html = '<textarea placeholder="多个单号请换行" class="express-text">';
        $('.modal-body').html(html);
        $('.modal-footer').find('a').attr('data-url',that.data('url'));
      });  
      
      //modal-action
      $('.mod-act').click(function() {
        $('#express-modal').find ('.close').click(); 
        var url = $(this).data('url');
        var data = $('textarea').val();
        $.ajax({
        url:url,
        type:'post',
        data:{number:data},
        success:function(res) {
          alert(res);
          $.pjax.reload({container:"#order-table",timeout: 5000});
        }
      });
      })
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
        position:absolute;
    }

    .open > button {
        position: relative;
        bottom: 5px;
    }

    .express-text {
        margin: 0px;
        width: 567px;
        height: 180px;
    }

    .input-file {
        display: none !important;
    }

</style>



