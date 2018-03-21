<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "oa_data_mine_detail".
 *
 * @property int $id
 * @property int $mid
 * @property string $parentId
 * @property string $proName
 * @property string $description
 * @property string $tags
 * @property string $childId
 * @property string $color
 * @property string $proSize
 * @property int $quantity
 * @property string $price
 * @property string $msrPrice
 * @property int $shipping
 * @property string $shippingWeight
 * @property string $shippingTime
 * @property string $varMainImage
 * @property string $extra_image0
 * @property string $extra_image1
 * @property string $extra_image2
 * @property string $extra_image3
 * @property string $extra_image4
 * @property string $extra_image5
 * @property string $extra_image6
 * @property string $extra_image7
 * @property string $extra_image8
 * @property string $extra_image9
 * @property string $extra_image10
 */
class OaDataMineDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_data_mine_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mid', 'quantity', 'shipping'], 'integer'],
            [['parentId', 'proName', 'description', 'tags', 'childId', 'color', 'proSize', 'shippingTime', 'varMainImage', 'extra_image0', 'extra_image1', 'extra_image2', 'extra_image3', 'extra_image4', 'extra_image5', 'extra_image6', 'extra_image7', 'extra_image8', 'extra_image9', 'extra_image10'], 'string'],
            [['price', 'msrPrice', 'shippingWeight'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mid' => 'Mid',
            'parentId' => 'Parent ID',
            'proName' => 'Pro Name',
            'description' => 'Description',
            'tags' => 'Tags',
            'childId' => 'Child ID',
            'color' => 'Color',
            'proSize' => 'Pro Size',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'msrPrice' => 'Msr Price',
            'shipping' => 'Shipping',
            'shippingWeight' => 'Shipping Weight',
            'shippingTime' => 'Shipping Time',
            'varMainImage' => 'Var Main Image',
            'extra_image0' => 'Extra Image0',
            'extra_image1' => 'Extra Image1',
            'extra_image2' => 'Extra Image2',
            'extra_image3' => 'Extra Image3',
            'extra_image4' => 'Extra Image4',
            'extra_image5' => 'Extra Image5',
            'extra_image6' => 'Extra Image6',
            'extra_image7' => 'Extra Image7',
            'extra_image8' => 'Extra Image8',
            'extra_image9' => 'Extra Image9',
            'extra_image10' => 'Extra Image10',
        ];
    }
}
