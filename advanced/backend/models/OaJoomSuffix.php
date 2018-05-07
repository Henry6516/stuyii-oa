<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "oa_joom_suffix".
 *
 * @property int $nid
 * @property string $joomName 账号
 * @property string $joomSuffix 简称
 * @property string $imgCode 后缀
 * @property string $mainImg 主图
 * @property string $skuCode
 */
class OaJoomSuffix extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_joom_suffix';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['joomName'], 'required'],
            [['joomName'], 'unique'],
            [['joomName', 'imgCode', 'mainImg', 'skuCode'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nid' => 'Nid',
            'joomName' => 'Joom账号',
            'joomSuffix' => 'Joom账号简称',
            'imgCode' => '图片网址代码',
            'mainImg' => '主图',
            'skuCode' => 'SKU后缀',
        ];
    }
}
