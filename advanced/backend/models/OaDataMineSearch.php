<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OaDataMine;

/**
 * OaDataMineSearch represents the model behind the search form of `app\models\OaDataMine`.
 */
class OaDataMineSearch extends OaDataMine
{
    /**
     *  varMainImage
     */


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['proId', 'platForm', 'progress', 'creator', 'createTime', 'updateTime'], 'safe'],
            [['infoId','pyGoodsCode','devStatus','detailStatus','cat','subCat','goodsCode','MainImage'], 'safe']
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
    public function search($params)
    {
        $query = OaDataMine::find();
        $query->orderBy(['oa_data_mine.id' => SORT_DESC]);


        $connection = Yii::$app->db;
        $user = yii::$app->user->identity->username;
        $developer_sql = "SELECT users.userName FROM [user] users ,auth_assignment roles WHERE  users.id=roles.user_id and item_name like '%开发%'";
        $developer_ret = $connection->createCommand($developer_sql)->queryAll();
        $developers = [];
        foreach ($developer_ret as $key=>$value){
            $developers[] = $value['userName'];
        }


        if (!\in_array($user, $developers,true)){
            // 返回当前用户管辖下的用户
            $sql = "oa_P_users '{$user}'";

            $command = $connection->createCommand($sql);
            $result = $command->queryAll();
            $users = [];
            foreach ($result as $user) {
                $users[] = $user['userName'];
            }
            $query->andWhere(['in', 'creator', $users]);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => isset($params['pageSize']) && $params['pageSize'] ? $params['pageSize'] : 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        if($this->createTime) {
            $createTime = explode('/', $this->createTime);
            $query->andFilterWhere([
                'and',
                ['>=', 'convert(varchar(10),createTime,121)', $createTime[0]],
                ['<=', 'convert(varchar(10),createTime,121)', $createTime[1]],
            ]);

        }
        if($this->updateTime) {
            $updateTime = explode('/', $this->updateTime);
            $query->andFilterWhere([
                'and',
                ['>=', 'convert(varchar(10),updateTime,121)', $updateTime[0]],
                ['<=', 'convert(varchar(10),updateTime,121)', $updateTime[1]],
            ]);

        }

        $query->andFilterWhere(['like', 'proId', $this->proId])
            ->andFilterWhere(['like', 'platForm', $this->platForm])
            ->andFilterWhere(['like', 'progress', $this->progress])
            ->andFilterWhere(['like', 'detailStatus', $this->detailStatus])
            ->andFilterWhere(['like', 'devStatus', $this->devStatus])
            ->andFilterWhere(['like', 'cat', $this->cat])
            ->andFilterWhere(['like', 'subCat', $this->subCat])
            ->andFilterWhere(['like', 'goodsCode', $this->goodsCode])
            ->andFilterWhere(['like', 'pyGoodsCode', $this->pyGoodsCode])
            ->andFilterWhere(['like', 'creator', $this->creator]);
        return $dataProvider;
    }
}
