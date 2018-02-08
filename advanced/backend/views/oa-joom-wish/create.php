<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\OaJoomWish */

$this->title = 'Create Oa Joom Wish';
$this->params['breadcrumbs'][] = ['label' => 'Oa Joom Wishes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-joom-wish-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
