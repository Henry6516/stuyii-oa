<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "oa_wishgoodssku".
 *
 * @property int $itemid
 * @property int $pid
 * @property int $sid
 * @property string $sku
 * @property string $color
 * @property string $size
 * @property int $inventory
 * @property string $price
 * @property string $shipping
 * @property string $msrp
 * @property string $shipping_time
 * @property string $linkurl
 * @property int $goodsskuid
 * @property double $Weight
 */
class OaWishgoodssku extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_wishgoodssku';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'sid', 'inventory', 'goodsskuid'], 'integer'],
            [['sku', 'color', 'size', 'shipping_time', 'linkurl'], 'string'],
            [['joomPrice','price', 'shipping', 'msrp', 'Weight'], 'number'],
            [['sid'], 'unique'],
            [['sku'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'itemid' => 'Itemid',
            'pid' => 'Pid',
            'sid' => 'Sid',
            'sku' => 'Sku',
            'color' => 'Color',
            'size' => 'Size',
            'inventory' => 'Inventory',
            'price' => 'Price',
            'shipping' => 'Shipping',
            'msrp' => 'Msrp',
            'shipping_time' => 'Shipping Time',
            'linkurl' => 'Linkurl',
            'goodsskuid' => 'Goodsskuid',
            'Weight' => 'Weight',
        ];
    }
}
