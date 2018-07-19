<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Channel */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channel-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="table-scrollable">
        <div class="tab-content">
            <div class="tab-pane active">
                <table id="table" class="table table-striped table-bordered table-hover order-column">
                    <thead>
                    <tr>
                        <th> 销售员</th>
                        <th> 推广状态</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $v): ?>
                        <tr>
                            <td><?php echo $v['saler']; ?></td>
                            <td>
                                <?php if($v['status'] === '未推广'){
                                    echo Html::label($v['status'],null,
                                        ['style' => 'color:red']);
                                }else{
                                    echo Html::label($v['status'],null,
                                        ['style' => 'color:green']);
                                }
                                ?>
                            </td>
                        </tr>
                        <tr style="display: none;" data-open="false">
                            <td colspan="8" class="show-<?php echo $v['saler']; ?>" data-show="false">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
