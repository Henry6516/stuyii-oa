<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "oa_taskSendee".
 *
 * @property int $taskid 任务ID
 * @property int $userid 任务接受人（处理人）ID
 * @property string $status 任务处理状态
 * @property string $updatedate 任务处理时间
 */
class OaTaskSendee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_taskSendee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['taskid','userid'], 'required'],
            [['taskid', 'userid'], 'integer'],
            [['status'], 'string'],
            [['updatedate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'taskid' => 'Taskid',
            'userid' => 'Userid',
            'status' => 'Status',
            'updatedate' => 'Updatedate',
        ];
    }
}
