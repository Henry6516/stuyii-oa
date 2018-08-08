<?php

use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaSupplierOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '同步采购单';
$this->params['breadcrumbs'][] = $this->title;
//var_dump($dataProvider->allModels);exit;
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
                            <input type="text" id="_csrf" name="billNumber"
                                   value="<?= isset($search) && $search['billNumber'] ? $search['billNumber'] : '' ?>"/>
                        </div><!-- /input-group -->
                    </div>
                    <div class="col-sm-1">
                        <button type="submit" class="btn query-btn btn-primary">查询</button>
                    </div>
                </div><!-- /.row -->
            </form>
            <button type="button" class="btn btn-danger synchronization" style="margin-bottom: 1%;margin-left: 1%">
                确定同步
            </button>

            <?php if (isset($dataProvider)): ?>
                <div class="div-data" data-data='<?= json_encode($dataProvider->allModels); ?>'></div>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'id' => 'order-list',
                    'columns' => [
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            /*'filterOptions' => [
                                'pluginOptions' => ['data-id' => function($model){return $model['nid'];}],
                            ],*/
                        ],
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'BillNumber',
                            'label' => '订单编号',
                            'format' => 'raw',
                            'filterWidgetOptions' => [
                                'showDefaultPalette' => false,
                                'pluginOptions' => ['class' => 123],
                            ],
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
                            'value' => function ($model) {
                                return substr($model['MakeDate'], 0, 19);
                            }
                        ],
                        [
                            'attribute' => 'DelivDate',
                            'label' => '到货时间',
                            //'format' => ['date', 'Y-m-d'],
                            'value' => function ($model) {
                                return substr($model['DelivDate'], 0, 10);
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

                <div class="col-lg-10">
                    <h3>订单明细</h3>
                    <table id="detail-table"
                           class="table table-striped table-checkable table-bordered table-hover order-column">
                        <thead>
                        <tr>
                            <th> 商品编码</th>
                            <th> 商品名称</th>
                            <th> 商品SKU码</th>
                            <th> 规格</th>
                            <th> 型号</th>
                            <th> 款式1</th>
                            <th> 款式2</th>
                            <th> 款式3</th>
                            <th> 单位</th>
                            <th> 采购数量</th>
                            <th> 含税单价</th>
                            <th> 含税金额</th>
                            <th> 税率</th>
                            <th> 税额</th>
                            <th> 金额</th>
                        </tr>
                        </thead>
                        <tbody class="table-detail-body" data-data="">
                        <?php if ($detailList): ?>
                            <?php foreach ($detailList as $v): ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $v['Goodscode']; ?></td>
                                    <td><?php echo $v['Goodsname']; ?></td>
                                    <td><?php echo $v['SKU']; ?></td>
                                    <td><?php echo $v['Class']; ?></td>
                                    <td><?php echo $v['Model']; ?></td>
                                    <td><?php echo $v['property1']; ?></td>
                                    <td><?php echo $v['property2']; ?></td>
                                    <td><?php echo $v['property3']; ?></td>
                                    <td><?php echo $v['Unit']; ?></td>
                                    <td><?php echo $v['amount']; ?></td>
                                    <td><?php echo $v['price']; ?></td>
                                    <td><?php echo $v['money']; ?></td>
                                    <td><?php echo $v['TaxRate']; ?></td>
                                    <td><?php echo $v['TaxMoney']; ?></td>
                                    <td><?php echo $v['AllMoney']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>

        </div>
    </div>
</div>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
<?php

$queryUrl = Url::toRoute(['query-order']);
$detailUrl = Url::toRoute(['query-detail']);


$js = <<< JS

window.onload = function () {
            $('table.kv-grid-table>tbody').find("tr").eq(0).addClass("table-color");
};
        
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
  
  //查看订单明细
  var flag = true;
  $('table.kv-grid-table>tbody>tr').on('click',function() {
      if(flag){
          $('.table-detail-body>tr').remove();//删除对应的订单详情
          $('table.kv-grid-table>tbody').find("tr").removeClass("table-color");
          $(this).addClass("table-color");
          var index = $(this).data('key');    
          var list = $('.div-data').data('data');
          var nid = 0;
          $.each(list,function(i,item) {
              if(i == index){
                  nid = item.nid;
                  return false;
              }
          });
          flag = false;
         $.ajax({
            url:'{$detailUrl}',
            type:'GET',
            data:{'id':nid},
            success:function(res) {
                //$('.table-detail-body>tr').remove();
                $('.table-detail-body').append(res);
            }
         });
         setTimeout(function() {
             flag = true;
         },2000);
      }else{
          return false;
      }
  });
  
  
  /*
    同步订单
   */
  $('.synchronization').click(function() {
      var keys = $('#order-list').yiiGridView('getSelectedRows');
      if(keys.length == 0) {
          alert("请选择要同步的订单！")
          return false;
      }
      var list = $('.div-data').data('data');
      var ids = new Array();
      $.each(keys,function(k,it) {
          $.each(list,function(i,item) {
                if(i == it){
                    ids.push(item.nid);
                    return false;
                }
          });
      });
      ids = JSON.stringify(ids);//数组转化成JSON
      $.ajax({
        url:'{$queryUrl}',
        type:'POST',
        data:{ids:ids},
        success:function(res) {
            console.log(res);
            alert(res);
            location.reload();
        }
      });
  })
});

JS;

$this->registerJs($js);

?>
<style>
    .table-color {
        background-color: #00c0ef !important;
    }
</style>
