<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\OaJoomSuffix */

//$this->title = $model->nid;
//$this->params['breadcrumbs'][] = ['label' => 'Oa Joom Suffixes', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-joom-suffix-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'joomName',
            'imgCode',
            'mainImg',
            'skuCode',
        ],
    ]) ?>

</div>
