<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "oa_data_mine".
 *
 * @property int $id
 * @property string $proId
 * @property string $platForm
 * @property string $progress
 * @property string $creator
 * @property string $createTime
 * @property string $updateTime
 */
class OaDataMine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_data_mine';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['proId', 'platForm', 'progress', 'creator'], 'string'],
            [['createTime', 'updateTime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'proId' => 'Pro ID',
            'platForm' => 'Plat Form',
            'progress' => 'Progress',
            'creator' => 'Creator',
            'createTime' => 'Create Time',
            'updateTime' => 'Update Time',
        ];
    }
}
