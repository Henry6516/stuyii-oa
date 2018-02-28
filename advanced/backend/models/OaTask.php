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
            'sendee' => '执行人',
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

    /** 计算任务进度
     * 注意处理已删除人员的处理的任务进度
     */
    public function getTaskSchedule($id)
    {
        //已处理人员数
        $completeNum = OaTaskSendee::find()->joinWith('user')
            ->where(['taskid' => $id, 'oa_taskSendee.status' => '已处理'])
            ->andWhere(['not', ['user.username' => null]])
            ->count();
        //未处理人员数
        $unfinishedNum = OaTaskSendee::find()->joinWith('user')
            ->where(['taskid' => $id, 'oa_taskSendee.status' => ''])
            ->andWhere(['not', ['user.username' => null]])
            ->count();
        $schedule = round($completeNum/($unfinishedNum + $completeNum) * 100, 2);
        return $schedule;
    }

    /**获取执行人列表
     * @param $arr //已有人员数组
     * @return string
     */
    public static function getUserList($arr = [])
    {
        $depList = $userList = [];
        //全部
        $all = [['id' => '全部', 'pId' => 0, 'name' => '全部', 'open' => true]];
        //职位数组
        $dep_sql = 'SELECT ISNULL(a.item_name,\'其他\') AS name FROM auth_assignment a GROUP BY ISNULL(a.item_name,\'其他\') ORDER BY ISNULL(a.item_name,\'其他\') ASC';
        $depart = Yii::$app->db->createCommand($dep_sql)->queryAll();
        //判断是不存在‘其他’选项，没有则添加
        $depart = in_array('其他', $depart) ? $depart : array_merge($depart, [['name' =>'其他']]);
        foreach ($depart as $k => $v){
            $item['pId'] = '全部';
            $item['name'] = $item['id'] = $v['name'];
            $depList[] = $item;
        }
        //人员数组
        $user_sql = 'SELECT u.id,u.username,ISNULL(a.item_name,\'其他\') AS name FROM [user] u LEFT JOIN auth_assignment a ON u.id = a.user_id';
        $user = Yii::$app->db->createCommand($user_sql)->queryAll();
        foreach ($user as $k => $v){
            $item['id'] = $v['id'];
            $item['pId'] = $v['name'];
            $item['name'] = $v['username'];
            if($arr && in_array($v['id'], $arr)){
                $item['checked'] = true;
            }else{
                $item['checked'] = false;
            }
            $userList[] = $item;
        }
        //var_dump(array_merge($all, $depList, $userList));exit;
        return json_encode(array_merge($all, $depList, $userList));
    }



}
