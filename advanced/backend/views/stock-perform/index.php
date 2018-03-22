<?php

use  yii\helpers\Html;
use \kartik\form\ActiveForm;
use  \kartik\grid\GridView;
use kartik\daterange\DateRangePicker;

$this->title = '备货产品表现';
?>
<?php //echo $this->render('_search'); ?>
<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css"
      integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<div class="product-perform-index">

    <!--搜索框开始-->
    <div class="box-body row">
        <?php $form = ActiveForm::begin([
            'action' => ['stock-perform/index'],
            'method' => 'get',
            'options' => ['class' => 'form-inline drp-container form-group col-lg-12'],
            'fieldConfig' => [
                'template' => '{label}<div class="form-group text-right">{input}{error}</div>',
                //'labelOptions' => ['class' => 'col-lg-3 control-label'],
                'inputOptions' => ['class' => 'form-control'],
            ],
        ]); ?>

        <?= $form->field($model, 'cat', ['template' => '{label}{input}', 'options' => ['class' => 'col-lg-2']])
            ->dropDownList($list, ['prompt' => '请选择开发员'])->label('开发员:') ?>


        <?= $form->field($model, 'code', [
            'template' => '{label}{input}{error}',
            'options' => ['class' => 'col-lg-3']
        ])->textInput()->label("商品编码:"); ?>

        <?= $form->field($model, 'create_range', [
            'template' => '{label}{input}{error}',
            //'addon' => ['prepend' => ['content' => '<i class="glyphicon glyphicon-calendar"></i>']],
            'options' => ['class' => 'col-lg-3']
        ])->widget(DateRangePicker::classname(), [
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
        ])->label("开发时间:"); ?>

        <div class="">
            <?= Html::submitButton('<i class="glyphicon glyphicon-hand-up"></i> 确定', ['class' => 'btn btn-primary']); ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <!--搜索框结束-->

    <!--列表开始-->
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
//    'filterModel'=>$searchModel,
        'showPageSummary' => true,
        'pjax' => true,
        'striped' => true,
        'hover' => true,
        'panel' => ['type' => 'primary', 'heading' => '类目表现'],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'GoodsCode',
                'header' => '商品编码',
                'width' => '150px',
                'value' => function ($model, $key, $index, $widget) {
                    return $model['GoodsCode'];
                },
                //'group' => true,  // enable grouping
                'pageSummary' => 'Page Summary',
                'pageSummaryOptions' => ['class' => 'text-right text-warning'],
            ],
            [
                'attribute' => 'goodsName',
                'label' => '商品名称',
                //'pageSummary' => true,
            ],
            [
                'attribute' => 'devDatetime',
                'label' => '开发日期',
                //'pageSummary' => true,
            ],
            [
                'attribute' => 'developer',
                'width' => '150px',
                'hAlign' => 'right',
                //'format' => ['decimal', 2],
                'label' => '开发员',
                //'pageSummary' => true,
                //'pageSummaryFunc' => GridView::F_AVG
            ],
            [
                'attribute' => 'Number',
                'width' => '150px',
                'hAlign' => 'right',
                'format' => ['decimal', 0],
                'label' => '库存',
                'pageSummary' => true
            ],
            [
                'attribute' => 'Money',
                'width' => '150px',
                'hAlign' => 'right',
                'format' => ['decimal', 2],
                'label' => '库存金额(￥)',
                'pageSummary' => true
            ],
            [
                'attribute' => 'SellCount1',
                'width' => '150px',
                'hAlign' => 'right',
                'format' => ['decimal', 0],
                'label' => '5天销量',
                'pageSummary' => true
            ],
            [
                'attribute' => 'SellCount2',
                'width' => '150px',
                'hAlign' => 'right',
                'format' => ['decimal', 0],
                'label' => '10天销量',
                'pageSummary' => true
            ],
            [
                'attribute' => 'SellCount3',
                'width' => '150px',
                'hAlign' => 'right',
                'format' => ['decimal', 0],
                'label' => '20天销量',
                'pageSummary' => true
            ],
        ],
    ]); ?>
    <!--列表结束-->
</div>




