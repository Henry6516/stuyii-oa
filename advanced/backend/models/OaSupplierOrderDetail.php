<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "oa_supplierOrderDetail".
 *
 * @property int $id
 * @property int $orderId
 * @property string $sku
 * @property string $image
 * @property string $supplierGoodsSku
 * @property string $goodsName
 * @property string $property1
 * @property string $property2
 * @property string $property3
 * @property int $purchaseNumber
 * @property string $purchasePrice
 * @property int $deliveryNumber
 */
class OaSupplierOrderDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oa_supplierOrderDetail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orderId', 'purchaseNumber', 'deliveryNumber'], 'integer'],
            [['sku', 'image', 'supplierGoodsSku', 'goodsName', 'property1', 'property2', 'property3'], 'string'],
            [['purchasePrice'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orderId' => 'Order ID',
            'sku' => 'SKU',
            'image' => '图片',
            'supplierGoodsSku' => '供应商SKU',
            'goodsName' => '商品名称',
            'property1' => '款式1',
            'property2' => '款式2',
            'property3' => '款式3',
            'purchaseNumber' => '采购数量',
            'purchasePrice' => '采购价格',
            'deliveryNumber' => '发货数量',
        ];
    }
}
