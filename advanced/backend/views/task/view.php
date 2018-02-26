<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\OaTask */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '任务中心', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$userid = Yii::$app->user->identity->getId();
$tasksdee = \backend\models\OaTaskSendee::findOne(['taskid' =>$model->taskid, 'userid' => $userid, 'status' => '']);


$completeUrl = Url::toRoute(['complete','id' => $model->taskid]);
$js = <<<JS
//单个处理
$(".complete").on('click',function() {
    var id = $(this).closest('tr').data('key');
    var flag = confirm('确定标记完成?');
    if(flag){
        $.ajax({
            url:'{$completeUrl}',
            type:'get',
            success:function(res) {
                alert(res);//传回结果信息
                location.reload();
            }
        });
    }
});
JS;
$this->registerJs($js);
?>

<div class="oa-task-view" style="margin-top: 20px">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <h3 class="box-title">
                        <?php if($tasksdee){ ?>
                            <?= Html::button('标记完成', ['class' => 'btn btn-success complete']) ?>
                        <?php } ?>
                        <?php if($model->userid == $userid){ ?>
                            <?= Html::a('更新', ['update', 'id' => $model->taskid], ['class' => 'btn btn-primary']);?>
                            <?= Html::a('删除', ['delete', 'id' => $model->taskid], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => '确定要删除该项任务？',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        <?php } ?>
                    </h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td class="col-xs-1">ID</td>
                            <td><?= $model->taskid; ?></td>
                        </tr>
                        <tr>
                            <td>标题</td>
                            <td><?= $model->title; ?></td>
                        </tr>
                        <tr>
                            <td>发布人</td>
                            <td><?= User::findOne($model->userid)['username']; ?></td>
                        </tr>
                        <tr>
                            <td>发布时间</td>
                            <td><?= $model->createdate; ?></td>
                        </tr>
                        <tr>
                            <td>内容</td>
                            <td><?= $model->description; ?></td>
                        </tr>
                        <tr>
                            <td>任务进度(%)</td>
                            <td>
                                <div class="col-xs-4">
                                    <div class="progress progress-xs progress-striped active" style="width: 85%;float: left">
                                        <div class="progress-bar progress-bar-success" style="width: <?= $schedule ? $schedule . '%':'0%'; ?>"></div>
                                    </div>
                                    <div style="float: right">
                                        <span class="badge bg-green"><?= $schedule ? $schedule . '%': '0%'; ?></span>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>已完成人员</td>
                            <td><?php
                                $complete = $unfinished ='';
                                if($completeName){
                                    foreach ($completeName as $k => $v){
                                        if($k == 0){
                                            $complete .= '<span class="badge bg-green">'.User::findOne($v['userid'])['username'].'</span>';
                                        }else{
                                            $complete .= ' ' . '<span class="badge bg-green"> '.User::findOne($v['userid'])['username'].'</span>';
                                        }
                                    }
                                    echo $complete;
                                }
                                ?></td>
                        </tr>
                        <tr>
                            <td>未完成人员</td>
                            <td><?php
                                if($unfinishedName){
                                    foreach ($unfinishedName as $k => $v){
                                        if($k == 0){
                                            $unfinished .= '<span class="badge bg-red">'.User::findOne($v['userid'])['username'].'</span>';
                                        }else{
                                            $unfinished .= ' ' . '<span class="badge bg-red"> '.User::findOne($v['userid'])['username'].'</span>';
                                        }
                                    }
                                    echo $unfinished;
                                }
                                ?></td>
                        </tr>

                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>
</div>
