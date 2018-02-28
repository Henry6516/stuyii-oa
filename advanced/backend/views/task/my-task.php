<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = '我发出的';
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
            'active' => true,
        ],
        [
            'label' => '未完成的'.$task_num,
            'url' => Url::to(['unfinished']),
            'headerOptions' => ["id" => 'tab2'],
            'options' => ['id' => 'unfinished-task'],
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
            ['class' => 'kartik\grid\ActionColumn'],
            [
                'attribute' => 'title',
                'width' => '400px',
                'format' => 'raw',
            ],
            [
                'attribute' => 'username',
                'label' => '创建人',
                'value' => 'user.username'
            ],
            [
                'attribute' => 'schedule',
                'width' => '400px',
                'label' => '任务进度(%)',
                'format' => 'raw',
                'value' => function($model){
                    return '<div class="col-xs-10 col-xs-offset-1">
                                    <div class="progress progress-xs progress-striped active" style="width: 80%;float: left">
                                        <div class="progress-bar progress-bar-success" style="width: '.
                        ($model->schedule ? ($model->schedule . '%'):'0%') .'"></div>
                                    </div><div style="float: right"><span class="badge bg-green">'.
                        ($model->schedule ? ($model->schedule . '%'): '0%') .'</span></div></div>';
                },
            ],
            [
                'attribute' => 'createdate',
                'format' => 'raw',
                'value' => function ($model) {
                    return "<span class='cell'>" . substr(strval($model->createdate), 0, 10) . "</span>";
                },
                'width' => '400px',
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

</div>


