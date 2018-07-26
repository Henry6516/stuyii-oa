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
            'sku' => 'Sku',
            'image' => 'Image',
            'supplierGoodsSku' => 'Supplier Goods Sku',
            'goodsName' => 'Goods Name',
            'property1' => 'Property1',
            'property2' => 'Property2',
            'property3' => 'Property3',
            'purchaseNumber' => 'Purchase Number',
            'purchasePrice' => 'Purchase Price',
            'deliveryNumber' => 'Delivery Number',
        ];
    }
}
