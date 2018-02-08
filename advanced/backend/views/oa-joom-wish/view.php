<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OaJoomWish */

$this->title = $model->nid;
$this->params['breadcrumbs'][] = ['label' => 'Oa Joom Wishes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-joom-wish-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->nid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->nid], [
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
            'nid',
            'greater_equal',
            'less',
            'added_price',
            'createDate',
            'updateDate',
        ],
    ]) ?>

</div>
