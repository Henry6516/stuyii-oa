<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplier */

$this->title = $model->supplierName;
$this->params['breadcrumbs'][] = ['label' => '供应商管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-supplier-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'supplierName',
            'contactPerson1',
            'phone1',
            'contactPerson2',
            'phone2',
            'address',
            'link1',
            'link2',
            'link3',
            'link4',
            'link5',
            'link6',
            'paymentDays',
            'payChannel',
            'purchase',
            'createtime',
            'updatetime',
        ],
    ]) ?>

</div>
