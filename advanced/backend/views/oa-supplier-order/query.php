<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaSupplierOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '同步采购单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-supplier-order-query" style="margin-top: 1%;">

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
        <button type="button" class="btn btn-danger">确定同步</button>
    </div>

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
    // $('input[name="daterange"]').val(start + '-' + end);
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

