<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierGoods */

$this->title = '' . $model->goodsName;
$this->params['breadcrumbs'][] = ['label' => '供应商产品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->goodsName, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="oa-supplier-goods-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
