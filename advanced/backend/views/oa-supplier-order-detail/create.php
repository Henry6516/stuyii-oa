<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OaSupplierOrderDetail */

$this->title = 'Create Oa Supplier Order Detail';
$this->params['breadcrumbs'][] = ['label' => 'Oa Supplier Order Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-supplier-order-detail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
