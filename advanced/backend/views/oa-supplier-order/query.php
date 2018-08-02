<?php

use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaSupplierOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '同步采购单';
$this->params['breadcrumbs'][] = $this->title;
//var_dump( Yii::$app->request->getCsrfToken());exit;
?>
<div class="oa-supplier-order-query" style="margin-top: 1%;">
    <div class="col-md-12">
        <div class="box box-info">
            <form class="query-form" style="margin-top: 1%;margin-left: 1%" method="get"
                  action="<?= Url::toRoute(['oa-supplier-order/query']) ?>">
                <div class="row form-group">
                    <div class="col-sm-2">
                        <div class="input-group">
                            <span class="input-group-addon" id="sizing-addon1">供应商名称</span>
                            <input name="supplierName" type="text" class="form-control" placeholder=""
                                   value="<?= isset($search) && $search['supplierName'] ? $search['supplierName'] : '' ?>">
                        </div><!-- /input-group -->
                    </div><!-- /.col-lg-3 -->
                    <div class="col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon" id="sizing-addon1">下单时间</span>
                            <input type="text" class="form-control" name="daterange"
                                   value="<?= isset($search) && $search['daterange'] ? $search['daterange'] : '' ?>"/>
                        </div><!-- /input-group -->
                    </div><!-- /.col-lg-3 -->
                    <div class="col-sm-2">
                        <div class="input-group">
                            <span class="input-group-addon" id="sizing-addon1">产品编码</span>
                            <input name="goodsCode" type="text" class="form-control" placeholder=""
                                   value="<?= isset($search) && $search['goodsCode'] ? $search['goodsCode'] : '' ?>">
                        </div><!-- /input-group -->
                    </div><!-- /.col-lg-3 -->
                    <div class="col-sm-2">
                        <div class="input-group">
                            <span class="input-group-addon" id="sizing-addon1">SKU</span>
                            <input name="sku" type="text" class="form-control" placeholder=""
                                   value="<?= isset($search) && $search['sku'] ? $search['sku'] : '' ?>">
                        </div><!-- /input-group -->
                    </div><!-- /.col-lg-3 -->
                    <div class="col-sm-2">
                        <div class="input-group">
                            <span class="input-group-addon" id="sizing-addon1">采购单号</span>
                            <input name="billNumber" type="text" class="form-control" placeholder=""
                                   value="<?= isset($search) && $search['billNumber'] ? $search['billNumber'] : '' ?>">
                        </div><!-- /input-group -->
                    </div>
                    <div class="col-sm-1">
                        <input type="hidden" id="_csrf" name="_csrf"
                               value="<?php echo Yii::$app->request->getCsrfToken() ?>"/>
                        <button type="submit" class="btn query-btn btn-primary">查询</button>
                    </div>
                </div><!-- /.row -->
            </form>
            <button type="button" class="btn btn-danger" style="margin-bottom: 1%;margin-left: 1%">确定同步</button>

            <?php if (isset($dataProvider)): ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\CheckboxColumn'],
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view}',
                        ],
                        [
                            'attribute' => 'BillNumber',
                            'label' => '订单编号',
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'SupplierName',
                            'label' => '供应商名称',
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'CHECKfLAG',
                            'label' => '订单状态',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if ($model['CHECKfLAG'] == 1) {
                                    return '已审核';
                                } else {
                                    return '未审核';
                                }
                            }
                        ],
                        [
                            'attribute' => 'Recorder',
                            'label' => '采购员',
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'MakeDate',
                            'label' => '订单时间',
                            'format' => 'raw',
                            'value' => function($model){
                                return substr($model['MakeDate'],0,19);
                            }
                        ],
                        [
                            'attribute' => 'DelivDate',
                            'label' => '到货时间',
                            //'format' => ['date', 'Y-m-d'],
                            'value' => function($model){
                                return substr($model['DelivDate'],0,10);
                            }
                        ],
                        [
                            'attribute' => 'OrderAmount',
                            'label' => '订单数量',
                            'format' => ['decimal', 2],
                        ],
                        [
                            'attribute' => 'OrderMoney',
                            'label' => '订单金额',
                            'format' => ['decimal', 2],
                        ],
                    ],
                ]); ?>

            <?php endif; ?>

        </div>
    </div>
</div>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
<?php

$queryUrl = Url::toRoute(['query - order']);


$js = <<< JS
  
$(function() {
  var start = moment();
  var end = moment();
  function cb(start, end) {
    // $('input[name = "daterange"]').val(start + ' - ' + end);
  }
  /*
  date range picker
   */
  $('input[name = "daterange"]').daterangepicker({  
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
  $(' . query - btn').click(function() {
    var query = $(' . query - form').serialize();
    $.ajax({
      url:'{
        $queryUrl}',
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

