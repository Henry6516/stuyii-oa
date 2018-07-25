<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierOrder */

$this->title = '创建订单';
$this->params['breadcrumbs'][] = ['label' => '采购订单', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-supplier-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
