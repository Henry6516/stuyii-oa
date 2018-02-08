<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "oa_joom_wish".
 *
 * @property int $nid
 * @property string $greater_equal
 * @property string $less
 * @property string $added_price
 * @property string $createDate
 * @property string $updateDate
 */
class OaJoomWish extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_joom_wish';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nid'], 'integer'],
            [['greater_equal', 'less', 'added_price'], 'number'],
            [['createDate', 'updateDate'], 'safe'],
            [['nid'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nid' => '编号',
            'greater_equal' => '大于等于重量(g)',
            'less' => '小于重量(g)',
            'added_price' => '价格增加($)',
            'createDate' => '创建时间',
            'updateDate' => '更新时间',
        ];
    }
}
