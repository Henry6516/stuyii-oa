<?php
/* @var $this yii\web\View */

$this->title = '修改任务';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="task-create" style="margin-top: 20px">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">修改任务</h3>
        </div>
        <?php echo $this->render('_form', ['model' => $model, 'userList' => $userList, 'user' => $user]); ?>
    </div>
</div>
