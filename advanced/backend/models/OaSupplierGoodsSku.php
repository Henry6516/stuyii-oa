<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "oa_supplierGoodsSku".
 *
 * @property int $id
 * @property int $supplierGoodsId
 * @property string $sku
 * @property string $property1
 * @property string $property2
 * @property string $property3
 * @property string $costPrice
 * @property string $purchasPrice
 * @property string $weight
 * @property string $image
 * @property string $lowestPrice
 * @property int $purchasNumber
 * @property string $supplierGoodsSku
 */
class OaSupplierGoodsSku extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'oa_supplierGoodsSku';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['supplierGoodsId', 'purchasNumber'], 'integer'],
            [['sku', 'property1', 'property2', 'property3', 'image', 'supplierGoodsSku'], 'string'],
            [['costPrice', 'purchasPrice', 'weight', 'lowestPrice'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplierGoodsId' => 'Supplier Goods ID',
            'sku' => 'Sku',
            'property1' => 'Property1',
            'property2' => 'Property2',
            'property3' => 'Property3',
            'costPrice' => 'Cost Price',
            'purchasPrice' => 'Purchas Price',
            'weight' => 'Weight',
            'image' => 'Image',
            'lowestPrice' => 'Lowest Price',
            'purchasNumber' => 'Purchas Number',
            'supplierGoodsSku' => 'Supplier Goods Sku',
        ];
    }
}
