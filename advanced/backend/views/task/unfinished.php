<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = '未完成的';
$this->params['breadcrumbs'][] = $this->title;

$userid = yii::$app->user->identity->getId();
//获取待处理任务数量
$task_num = \backend\models\OaTaskSendee::find()->where(['userid' => $userid, 'status' => ''])->count();
$js = <<<JS
$("#tab2 > a").html('未完成的<sup class="label label-danger">{$task_num}</sup>');
JS;
$this->registerJs($js);

?>

<?= \yii\bootstrap\Tabs::widget([
    'items' => [
        [
            'label' => '我发出的',
            'url' => Url::to(['my-task']),
            'headerOptions' => ["id" => 'tab4'],
            'options' => ['id' => 'my-task'],
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
    ],
]); ?>


<div class="oa-goodsinfo-index" style="margin-top: 20px">
    <p>
        <?= Html::button('批量标记完成', ['id' => 'complete-lots', 'class' => 'btn btn-info']) ?>
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
                            'title' => '标记完成',
                            'aria-label' => '标记完成',
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
    var flag = confirm('确定标记完成?');
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
        alert('请选择要标记的任务！');
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


