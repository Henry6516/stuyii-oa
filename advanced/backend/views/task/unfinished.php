<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = '全部任务';
$this->params['breadcrumbs'][] = $this->title;
?>


<?= \yii\bootstrap\Tabs::widget([
    'items' => [
        [
            'label' => '全部任务',
            'url' => Url::to(['index']),
            'headerOptions' => ["id" => 'tab1'],
            'options' => ['id' => 'all-task'],
        ],
        [
            'label' => '未完成的',
            'url' => Url::to(['unfinished']),
            'headerOptions' => ["id" => 'tab2'],
            'options' => ['id' => 'unfinished-task'],
            'active' => true,
        ],
        [
            'label' => '已完成的',
            'url' => Url::to(['finished']),
            'headerOptions' => ["id" => 'tab3'],
            'options' => ['id' => 'finished-task'],
        ],
        [
            'label' => '我发出的',
            'url' => Url::to(['my-task']),
            'headerOptions' => ["id" => 'tab4'],
            'options' => ['id' => 'my-task'],
        ],


    ],
]); ?>


<div class="oa-goodsinfo-index" style="margin-top: 20px">
    <p>
        <?= Html::button('批量处理任务', ['id' => 'complete-lots', 'class' => 'btn btn-info']) ?>
        <?= Html::a('发起任务', 'create', ['id' => 'task-create', 'class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showPageSummary' => true,//显示统计栏，默认为false
        'id' => 'oa-task',
        //'pjax' => true,
        //'floatHeader'=>true,//向下滚动时，标题栏可以fixed，默认为false
        'striped' => true,
        //'responsive' => true,//自适应，默认为true
        'hover' => true,//鼠标移动上去时，颜色变色，默认为false
//        'panel'=>['type'=>'primary', 'heading'=>'基本信息'],
        'columns' => [
            ['class' => 'kartik\grid\CheckboxColumn'],
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {complete}',
                'buttons' => [
                    'complete' => function ($url, $model, $key) {
                        $options = [
                            'title' => '处理任务',
                            'aria-label' => '处理任务',
                            'data-id' => $key,
                            'class' => 'index-input',
                            'id' => 'index-complete',
                        ];
                        return Html::a('<span  class="glyphicon glyphicon-send"></span>', '#', $options);
                    },
                ],
            ],
            [
                'attribute' => 'title',
                'width' => '500px',
                'format' => 'raw',
            ],
            [
                'attribute' => 'username',
                'label' => '创建人',
                'value' => 'user.username'
            ],
            [
                'attribute' => 'createdate',
                'format' => 'raw',
                'value' => function ($model) {
                    return "<span class='cell'>" . substr(strval($model->createdate), 0, 10) . "</span>";
                },
                'width' => '500px',
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'value' => Yii::$app->request->get('OaGoodsinfoSearch')['devDatetime'],
                        'convertFormat' => true,
                        'useWithAddon' => true,
                        'format' => 'php:Y-m-d H:i:s',
                        'todayHighlight' => true,
                        'locale' => [
                            'format' => 'YYYY-MM-DD',
                            'separator' => '/',
                            'applyLabel' => '确定',
                            'cancelLabel' => '取消',
                            'daysOfWeek' => false,
                        ],
                        'opens' => 'left',
                        //起止时间的最大间隔
                        /*'dateLimit' =>[
                            'days' => 300
                        ]*/
                    ]
                ]
            ],
        ],
    ]); ?>

    <?php
    $viewUrl = Url::toRoute('view');
    $completeUrl = Url::toRoute('complete');
    $completeLotsUrl = Url::toRoute('complete-lots');
    $js = <<<JS
//单个处理
$("#index-complete").on('click',function() {
    var id = $(this).closest('tr').data('key');
    var flag = confirm('确定处理该任务?');
    if(flag){
        $.ajax({
            url:'{$completeUrl}',
            type:'get',
            data:{id:id},
            success:function(res) {
                alert(res);//传回结果信息
                location.reload();
            }
        });
    }
});

//批量处理
$("#complete-lots").on('click',function() {
    ids = $("#oa-task").yiiGridView('getSelectedRows');
    if(ids.length == 0) {
        alert('请选择要处理的任务！');
        return false;
    }
    $.ajax({
        url:'{$completeLotsUrl}',
        type:'get',
        data:{ids:ids},
        success:function(res) {
            alert(res);
            location.reload();
        }
    });
});
JS;
    $this->registerJs($js); ?>


</div>


