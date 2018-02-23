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
            [['updatetime'], 'safe'],
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

    /** 关联任务
     */
    public function getTask()
    {
        //同样第一个参数指定关联的子表模型类名
        return $this->hasOne(OaTask::className(), ['taskid' => 'taskid']);
    }
}
