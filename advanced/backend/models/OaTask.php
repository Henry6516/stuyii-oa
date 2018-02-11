<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "oa_task".
 *
 * @property int $taskid 任务自增ID
 * @property int $userid 创建人ID
 * @property string $title 标题
 * @property string $description 内容
 * @property string $createdate 任务发布时间
 * @property string $updatedate 更新时间
 */
class OaTask extends \yii\db\ActiveRecord
{
    public $sendee;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','sendee'], 'required'],
            [['userid'], 'integer'],
            [['title', 'description'], 'string'],
            [['schedule', 'createdate', 'updatedate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => '标题',
            'description' => '内容',
            'createdate' => '创建时间',
        ];
    }
    /** 关联所有任务接收人
     */
    public function getTaskSendee()
    {
        //同样第一个参数指定关联的子表模型类名
        return $this->hasMany(OaTaskSendee::className(), ['taskid' => 'taskid']);
    }

    /** 关联用户
     */
    public function getUser()
    {
        //同样第一个参数指定关联的子表模型类名
        return $this->hasOne(User::className(), ['id' => 'userid']);
    }

    /** 获取执行人列表
     */
    public static function getUserList()
    {
        $data = User::find()->asArray()->all();
        $list = [];
        foreach ($data as $k => $v){
            $item = $v['username'];
            if($v['department']){
                $item = $v['username'].'--'.$v['department'];
            }
            $list[$v['id']] = $item;
        }
        return $list;
    }



}
