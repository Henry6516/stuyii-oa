<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierOrder */

$this->title = '更新订单' . $model->billNumber;
$this->params['breadcrumbs'][] = ['label' => '订单列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->billNumber;
?>
<div class="oa-supplier-order-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
