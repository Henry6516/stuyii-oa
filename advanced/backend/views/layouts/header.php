<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\OaTaskSendee;

/* @var $this \yii\web\View */
/* @var $content string */

$userid = yii::$app->user->identity->getId();
//获取待处理任务数量
$task_num = OaTaskSendee::find()->where(['userid' => $userid, 'status' => ''])->count();
//获取最新待处理的五个任务
$task_latest_list = OaTaskSendee::find()->joinWith('task')
    ->where(['oa_taskSendee.userid' => $userid, 'status' => ''])
    ->orderBy('oa_task.createdate DESC')
    ->limit(5)
    ->asArray()->all();
//var_dump($task_latest_list);exit;
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . "产品中心" . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- Messages: style can be found in dropdown.less
                根据不同的角色，可以提醒用户各状态新品的数量。
                -->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                        <?php if ($task_num) { ?>
                            <span class="label label-danger"><?= $task_num ?></span>
                        <?php } ?>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if ($task_latest_list) { ?>
                            <li class="header">你有几个待处理的任务</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul  class="menu">
                                    <!-- start message -->
                                    <?php foreach ($task_latest_list as $res) { ?>
                                        <li>
                                            <a href="<?= Url::toRoute(['/task/view', 'id' => $res['taskid']]) ?>">
                                                <div class="pull-left">
                                                    <img src="<?= $directoryAsset ?>/img/user4-128x128.jpg"
                                                         class="img-circle"
                                                         alt="User Image"/>
                                                </div>
                                                <h4>
                                                    <?= $res['task']['title'] ?>
                                                    <small>
                                                        <i class="fa fa-clock-o"></i> <?= substr(strval($res['task']['createdate']), 0, 16) ?>
                                                    </small>
                                                </h4>
                                                <p><?= mb_substr(strip_tags($res['task']['description']), 0, 15) ?></p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <!-- end message -->
                                </ul>
                            </li>
                            <li class="footer"><a href="<?= Url::toRoute(['/task/unfinished']) ?>">查看所有待处理任务</a></li>
                        <?php } else { ?>
                            <li class="header text-center">暂无待处理任务!</li>
                        <?php } ?>
                    </ul>
                </li>
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span id="notify-num" class="label label-warning">10</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header" style="text-align:center">您有新的通知</li>
                        <li>
                            <ul id="notify" class="menu">
                                <li>
                                    <a href="#">
                                        <i class="fa fa-truck text-red"></i>编号：XXXX 已采集完毕！
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-truck text-red"></i>编号：XXXX 已采集完毕！
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-truck text-red"></i>编号：XXXX 已采集完毕！
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <i class="fa fa-truck text-red"></i>编号：XXXX 已采集完毕！
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-truck text-red"></i>编号：XXXX 已采集完毕！
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">查看全部</a></li>
                    </ul>
                </li>

                <!-- <li class="dropdown tasks-menu">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                         <i class="fa fa-flag-o"></i>
                         <span class="label label-danger">9</span>
                     </a>
                     <ul class="dropdown-menu">
                         <li class="header">你有几项任务去做</li>
                         <li>
                             <ul class="menu">
                                 <li>
                                     <a href="#">
                                         <h3>
                                             Design some buttons
                                             <small class="pull-right">20%</small>
                                         </h3>
                                         <div class="progress xs">
                                             <div class="progress-bar progress-bar-aqua" style="width: 20%"
                                                  role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                  aria-valuemax="100">
                                                 <span class="sr-only">20% Complete</span>
                                             </div>
                                         </div>
                                     </a>
                                 </li>
                                 <li>
                                     <a href="#">
                                         <h3>
                                             Create a nice theme
                                             <small class="pull-right">40%</small>
                                         </h3>
                                         <div class="progress xs">
                                             <div class="progress-bar progress-bar-green" style="width: 40%"
                                                  role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                  aria-valuemax="100">
                                                 <span class="sr-only">40% Complete</span>
                                             </div>
                                         </div>
                                     </a>
                                 </li>
                                 <li>
                                     <a href="#">
                                         <h3>
                                             Some task I need to do
                                             <small class="pull-right">60%</small>
                                         </h3>
                                         <div class="progress xs">
                                             <div class="progress-bar progress-bar-red" style="width: 60%"
                                                  role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                  aria-valuemax="100">
                                                 <span class="sr-only">60% Complete</span>
                                             </div>
                                         </div>
                                     </a>
                                 </li>
                                 <li>
                                     <a href="#">
                                         <h3>
                                             Make beautiful transitions
                                             <small class="pull-right">80%</small>
                                         </h3>
                                         <div class="progress xs">
                                             <div class="progress-bar progress-bar-yellow" style="width: 80%"
                                                  role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                  aria-valuemax="100">
                                                 <span class="sr-only">80% Complete</span>
                                             </div>
                                         </div>
                                     </a>
                                 </li>
                             </ul>
                         </li>
                         <li class="footer">
                             <a href="#">View all tasks</a>
                         </li>
                     </ul>
                 </li>-->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $directoryAsset ?>/img/user4-128x128.jpg" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= Yii::$app->user->identity->username ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $directoryAsset ?>/img/user4-128x128.jpg" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                你好,<?= Yii::$app->user->identity->username ?>
                                <small><?= date("Y-m-d", time()) ?></small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#"></a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#"></a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#"></a>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">资料</a>
                            </div>
                            <div class="pull-right">
                                <?= Html::a('退出', ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<?php
$js = <<< JS

var ws = new WebSocket("ws://192.168.0.134:8899/");
 
ws.onopen = function(evt) { 
  console.log("Connection open ..."); 
  ws.send("Hello WebSockets!");
};
 
ws.onmessage = function(evt) {
  console.log( "Received Message: " + evt.data);
  ele = '<li><a href="#"><i class="fa fa-truck text-red"></i>任务编号：'+ evt.data +' 已采集完毕！</a></li>'
  $('#notify').append(ele);
  var num = $('#notify').children('li').length;
  $('#notify-num').text(num);
};
 
ws.onclose = function(evt) {
  console.log("Connection closed.");
};      
 

JS;

$this->registerJs($js);
?>