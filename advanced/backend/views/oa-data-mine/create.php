<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\OaDataMine */

$this->title = 'Create Oa Data Mine';
$this->params['breadcrumbs'][] = ['label' => 'Oa Data Mines', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-data-mine-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
