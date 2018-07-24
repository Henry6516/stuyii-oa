<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplier */

$this->title = '添加新供应商';
$this->params['breadcrumbs'][] = ['label' => '供应商管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-supplier-create">

    <h1><?php //echo Html::encode($this->title) ?></h1>

    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">添加新供应商</h3>
            </div>
            <?php echo $this->render('_form', ['model' => $model,'data' => $data]) ?>
        </div>
    </div>
</div>
