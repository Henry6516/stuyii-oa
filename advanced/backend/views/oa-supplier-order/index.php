<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaSupplierOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '供应商订单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-supplier-order-index" style="margin-top: 1%;">

    <form class="query-form">
        <div class="row form-group">
            <div class="col-sm-2">
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon1">供应商名称</span>
                    <input name="supplierName" type="text" class="form-control" placeholder="">
                </div><!-- /input-group -->
            </div><!-- /.col-lg-3 -->
            <div class="col-sm-3">
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon1">下单时间</span>
                    <input type="text" class="form-control" name="daterange"/>
                </div><!-- /input-group -->
            </div><!-- /.col-lg-3 -->
            <div class="col-sm-2">
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon1">产品编码</span>
                    <input name="goodsCode" type="text" class="form-control" placeholder="">
                </div><!-- /input-group -->
            </div><!-- /.col-lg-3 -->
            <div class="col-sm-2">
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon1">SKU</span>
                    <input name="sku" type="text" class="form-control" placeholder="">
                </div><!-- /input-group -->
            </div><!-- /.col-lg-3 -->
            <div class="col-sm-2">
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon1">采购单号</span>
                    <input name="billNumber" type="text" class="form-control" placeholder="">
                </div><!-- /input-group -->
            </div>
            <div class="col-sm-1">
                <button type="submit" class="btn query-btn btn-primary">查询</button>
            </div>
        </div><!-- /.row -->
    </form>

    <div style="margin-top: 1%;margin-bottom: 1%">

        <div class="btn-group">
            <button type="button" class="btn  btn-info">常用操作</button>
            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li role="separator" class="divider"></li>
                <li><?= Html::a('创建订单', ['create'], ['class' => '']) ?></li>
            </ul>
        </div>
    </div>
    <?php try {echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
                'template' => '{view} {update} {sync} {pay} {delivery} {inputExpress} {inputDeliveryOrder} {check} ',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return "<li><a target='_blank' href=$url>订单明细</a></li>";
                    },
                    'update' => function ($url, $model) {
                        return "<li><a target='_blank' href=$url>编辑订单</a></li>";
                    },
                    'sync' => function () {
                        return '<li><a href="#">同步普源数据</a></li>';
                    },
                    'delivery' => function () {
                        return '<li><a href="#">发货</a></li>';
                    },
                    'pay' => function () {
                        return '<li><a href="#">付款</a></li>';
                    },
                    'inputExpress' => function () {
                        return '<li><a href="#">导入物流单号</a></li>';
                    },
                    'check' => function () {
                        return '<li><a href="#">审核单据</a></li>';
                    },
                    'inputDeliveryOrder' => function () {
                        return '<li><a href="#">导入发货单</a></li>';
                    },
                ],
            ],
            'supplierName',
            'goodsCode',
            'billNumber',
            'billStatus',
            'purchaser',
            'syncTime',
            'totalNumber',
            'amt',
            'expressNumber',
            'paymentStatus',
        ],
    ]);}
    catch (Exception  $why) {
        throw new \Exception($why);
    }
     ?>
</div>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?php

$queryUrl = Url::toRoute(['query-order']);


$js = <<< JS
  
$(function() {
  var start = moment();
  var end = moment();
  function cb(start, end) {
    $('input[name="daterange"]').val(start + '-' + end);
  }
  /*
  date range picker
   */
  $('input[name="daterange"]').daterangepicker({  
    startDate: start,
    endDate: end,
    ranges: {
           "近七天": [moment().subtract(6,'days'), moment()],
           '本周' : [moment().startOf('week'), moment().endOf('week')]
        },
    locale: {
        "format": "YYYY-MM-DD",
        "separator": " - ",
        "applyLabel": "确定",
        "cancelLabel": "取消",
        "customRangeLabel"  :"自选时间",
        "daysOfWeek": [
            "日",
            "一",
            "二",
            "三",
            "四",
            "五",
            "六"
        ],
        "monthNames": [
            "一月",
            "二月",
            "三月",
            "四月",
            "五月",
            "六月",
            "七月",
            "八月",
            "九月",
            "十月",
            "十一月",
            "十二月"
        ]
        
    }
    
  }, cb);
  
  /*
  query submit
   */
  $('.query-btn').click(function() {
    var query = $('.query-form').serialize();
    debugger;
    $.ajax({
      url:'{$queryUrl}',
      type:'POST',
      data:query,
      success:function(res) {
        console.log(res);
      }
    });
  })
});

JS;

$this->registerJs($js);

?>

