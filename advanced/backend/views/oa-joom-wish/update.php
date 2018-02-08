<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OaJoomWish */

$this->title = 'Update Oa Joom Wish: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Oa Joom Wishes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nid, 'url' => ['view', 'id' => $model->nid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="oa-joom-wish-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
