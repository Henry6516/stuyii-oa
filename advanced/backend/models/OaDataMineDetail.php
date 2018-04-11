<?php

namespace app\models;

use Yii;
use backend\models\GoodsCats;
use yii\helpers\ArrayHelper;

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
    public $cat;
    public $subCat;


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
            [['MainImage', 'parentId', 'proName', 'description', 'tags', 'childId', 'color', 'proSize', 'shippingTime', 'varMainImage', 'extra_image0', 'extra_image1', 'extra_image2', 'extra_image3', 'extra_image4', 'extra_image5', 'extra_image6', 'extra_image7', 'extra_image8', 'extra_image9', 'extra_image10'], 'string'],
            [['price', 'msrPrice', 'shippingWeight'], 'number'],
            [['cat', 'subCat'], 'safe'],
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
            'parentId' => '商品编码',
            'proName' => '标题',
            'description' => '描述',
            'tags' => '标签',
            'childId' => 'Unique ID',
            'color' => '颜色',
            'proSize' => '型号',
            'quantity' => '数量',
            'price' => '价格',
            'msrPrice' => '原价',
            'shipping' => '运费',
            'shippingWeight' => '重量',
            'shippingTime' => '配送时间',
            'varMainImage' => '变体主图',
            'MainImage' => '主图',
            'cat' => '主类目',
            'subCat' => '子类目',
            'extra_image0' => '附加图#1',
            'extra_image1' => '附加图#2',
            'extra_image2' => '附加图#3',
            'extra_image3' => '附加图#4',
            'extra_image4' => '附加图#5',
            'extra_image5' => '附加图#6',
            'extra_image6' => '附加图#7',
            'extra_image7' => '附加图#8',
            'extra_image8' => '附加图#9',
            'extra_image9' => '附加图#10',
            'extra_image10' => '附加图#11',
        ];
    }

    /*
     * @brief join with oa-data-mine
     */
    public function getoa_data_mine()
    {
        return $this->hasOne(OaDataMine::className(),['id'=>'mid']);
    }

}
