<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplier */

$this->title = '编辑供应商: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => '供应商管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->supplierName, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="oa-supplier-update">

    <h1><?php //echo Html::encode($this->title) ?></h1>
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">编辑供应商</h3>
            </div>
            <?php echo $this->render('_form', ['model' => $model, 'data' => $data]) ?>
        </div>
    </div>
</div>
