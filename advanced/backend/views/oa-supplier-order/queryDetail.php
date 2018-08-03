<?php


/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaSupplierOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<?php if ($detailList): ?>
    <?php foreach ($detailList as $v): ?>
        <tr class="odd gradeX">
            <td><?php echo $v['Goodscode']; ?></td>
            <td><?php echo $v['Goodsname']; ?></td>
            <td><?php echo $v['SKU']; ?></td>
            <td><?php echo $v['Class']; ?></td>
            <td><?php echo $v['Model']; ?></td>
            <td><?php echo $v['property1']; ?></td>
            <td><?php echo $v['property2']; ?></td>
            <td><?php echo $v['property3']; ?></td>
            <td><?php echo $v['Unit']; ?></td>
            <td><?php echo $v['amount']; ?></td>
            <td><?php echo $v['price']; ?></td>
            <td><?php echo $v['money']; ?></td>
            <td><?php echo $v['TaxRate']; ?></td>
            <td><?php echo $v['TaxMoney']; ?></td>
            <td><?php echo $v['AllMoney']; ?></td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>

