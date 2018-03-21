<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-01-17
 * Time: 11:44
 */

use yii\helpers\Url;
use  yii\helpers\Html;
use \kartik\form\ActiveForm;
use kartik\daterange\DateRangePicker;


$this->title = '类别表现';
$cat = Url::toRoute('category');
$js = <<< JS
//设置背景色
$('body').css('background','#FFF');
//删除H1
$('h1').remove();
        $(function () {
                $.ajax({
                    url:'{$cat}', 
                    success:function (data) {
                       // var da = JSON.parse(data);  //推荐方法
                      init_chart('catAmt',data);
                      init_chart('catNum',data);
                    }
                });
            }); 
		$(document).on('ajaxStart', function(){
			$('.loading').show();
			return false;
		});
		$(document).on('ajaxComplete',function(e,x,o){
			$('.loading').hide();
			return false;
		});
JS;
$this->registerJs($js);
?>
<div class="cat-perform-index">
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- ECharts单文件引入 标签式单文件引入-->
    <script src="http://echarts.baidu.com/build/dist/echarts-all.js"></script>
    <?php
    echo Html::jsFile('@web/js/LRU.js');
    echo Html::jsFile('@web/js/color.js');
    ?>
    <script>
        var colorList = [
            '#C1232B', '#B5C334', '#FCCE10', '#E87C25', '#27727B',
            '#FE8463', '#9BCA63', '#FAD860', '#F3A43B', '#60C0DD',
            '#D7504B', '#C6E579', '#F4E001', '#F0805A', '#26C0C0',
            '#ff7f50', '#87cefa', '#da70d6', '#32cd32', '#6495ed',
            '#ff69b4', '#ba55d3', '#cd5c5c', '#ffa500', '#40e0d0'
        ];
        var itemStyle = {
            normal: {
                color: function (params) {
                    if (params.dataIndex < 0) {
                        // for legend
                        return lift(colorList[colorList.length - 1], params.seriesIndex * 0.1);
                    }
                    else {
                        // for bar
                        return lift(colorList[params.dataIndex], params.seriesIndex * 0.1);
                    }
                },
                barBorderRadius: [5, 8, 8, 8]
            },
        };
    </script>
    <body>

    <!--搜索框开始-->
    <?php $form = ActiveForm::begin([
        'action' => ['cat-perform/cat'],
        'method' => 'get',
        'options' => ['class' => 'form-inline drp-container form-group col-lg-12'],
        'fieldConfig' => [
            'template' => '{label}<div class="form-group text-right">{input}{error}</div>',
            //'labelOptions' => ['class' => 'col-lg-3 control-label'],
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <?= $form->field($model, 'cat', ['template' => '{label}{input}', 'options' => ['class' => 'col-lg-2']])
        ->dropDownList($list, ['prompt' => '请选择主类目'])->label('主类目:') ?>

    <?= $form->field($model, 'type', [
        'template' => '{label}{input}',
        'options' => ['class' => 'col-lg-2']
    ])->dropDownList(['0' => '交易时间', '1' => '发货时间'], ['placeholder' => '交易类型'])->label('交易类型:'); ?>

    <?= $form->field($model, 'order_range', [
        'template' => '{label}{input}{error}',
        //'addon' => ['prepend' => ['content' => '<i class="glyphicon glyphicon-calendar"></i>']],
        'options' => ['class' => 'col-lg-3']
    ])->widget(DateRangePicker::classname(), [
        'pluginOptions' => [
            //'autoclose'=>true,
            'format' => 'yyyy-mm-dd',
        ]
    ])->label("<span style = 'color:red'>* 订单时间:</span>"); ?>

    <?= $form->field($model, 'create_range', [
        'template' => '{label}{input}{error}',
        //'addon' => ['prepend' => ['content' => '<i class="glyphicon glyphicon-calendar"></i>']],
        'options' => ['class' => 'col-lg-3']
    ])->widget(DateRangePicker::classname(), [
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ])->label("创建时间:"); ?>


    <div class="">
        <?= Html::submitButton('<i class="glyphicon glyphicon-hand-up"></i> 确定', ['class' => 'btn btn-primary']); ?>
    </div>
    <?php ActiveForm::end(); ?>
    <!--搜索框结束-->

    <!-- 为 ECharts 准备一个具备大小（宽高）的 DOM -->
    <div class="row">
        <div class="col-md-12">
            <div class="loading" style="display: none;">
                <center><img src="/img/loading.gif"></center>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 20px">
        <div id="catNum" style="width: 800px;height:480px;" class="col-lg-6">
        </div>
        <div id="catAmt" style="width: 800px;height:480px;" class="col-lg-6">
        </div>
    </div>
    <script type="text/javascript">
        function init_chart(id, row_data) {
            // 基于准备好的dom，初始化echarts实例
            var myChart = echarts.init(document.getElementById(id));
            var data = eval("(" + row_data + ")");
            var catNumber, catName, maxValue;
            maxValue = data.maxValue;
            if (id == 'catNum') {
                role = '款数';
                maxValue = 4000;
                catNumber = data.catNum;
                catName = data.name;
            } else if (id == 'catAmt') {
                role = '销售额($)';
                catNumber = data.catAmt;
                catName = data.name;
            }
            // 使用刚指定的配置项和数据显示图表。
            option = {
                title: {
                    text: '30天-类目' + role,
                    subtext: '企划部',
                    x: 'center'
                },
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    data: catName
                },
                toolbox: {
                    show: true,
                    feature: {
                        mark: {show: true},
                        dataView: {show: true, readOnly: false},
                        magicType: {
                            show: true,
                            type: ['funnel', 'pie'],
                            option: {
                                funnel: {
                                    x: '25%',
                                    width: '50%',
                                    funnelAlign: 'left',
                                    max: maxValue
                                },
                                pie:{
                                    radius: '55%',
                                    center: ['50%', '60%']
                                }
                            }
                        },
                        restore: {show: true},
                        saveAsImage: {show: true}
                    }
                },
                calculable: true,
                series: [
                    {
                        name: '访问来源',
                        type: 'funnel',
                        x: '25%',
                        width: '50%',
                        funnelAlign: 'left',
                        max: maxValue,
                        data: catNumber
                    }
                ]
            };
            myChart.setOption(option);
        }
    </script>
    </body>
</div>






