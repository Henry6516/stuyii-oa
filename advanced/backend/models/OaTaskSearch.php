<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OaTask;

/**
 * OaTaskSearch represents the model behind the search form of `backend\models\OaTask`.
 */
class OaTaskSearch extends OaTask
{

    public $username;//任务发起人
    public $sendee;//任务接收人

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['taskid', 'userid'], 'integer'],
            [['title', 'description', 'createdate', 'updatedate','username','schedule'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $unit)
    {
        $userid = Yii::$app->user->identity->getId();
        //按模块显示
        if($unit == 'index'){
            $query = OaTask::find()->joinWith('user')->joinWith('taskSendee')->orderBy('createdate DESC');
        }elseif ($unit == 'unfinished'){
            $query = OaTask::find()->joinWith('user')->joinWith('taskSendee')
            ->where(['oa_taskSendee.userid' => $userid, 'oa_taskSendee.status' => ''])->orderBy('createdate DESC');
        } elseif ($unit == 'finished'){
            $query = OaTask::find()->joinWith('user')->joinWith('taskSendee')
            ->where(['oa_taskSendee.userid' => $userid, 'oa_taskSendee.status' => '已处理'])->orderBy('createdate DESC');
        }else{
            $query = OaTask::find()->joinWith('user')->joinWith('taskSendee')
            ->where(['oa_task.userid' => $userid])->orderBy('createdate DESC');
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => isset($params['pageSize']) && $params['pageSize'] ? $params['pageSize'] : 20,
            ],
        ]);

        $dataProvider->setSort([
            'attributes' => [
                /* 其它字段不要动 */
                'title',
                'createdate',
                'schedule',
                'username' => [
                    'asc' => ['user.username' => SORT_ASC],
                    'desc' => ['user.username' => SORT_DESC],
                    'label' => '创建人'
                ],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'taskid' => $this->taskid,
            'userid' => $this->userid,
            'convert(varchar(10),createdate,121)' => $this->createdate,
            'convert(varchar(10),updatedate,121)' => $this->updatedate,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'user.username', $this->username])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
