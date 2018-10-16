<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OaGoodsinfo;
use yii\helpers\ArrayHelper;

/**
 * OaGoodsinfoSearch represents the model behind the search form about `backend\models\OaGoodsinfo`.
 */
class OaGoodsinfoSearch extends OaGoodsinfo
{
    /**
     * @inheritdoc
     *
     */


    public $GoodsName; //<=====就是加在这里
    public $vendor1;
    public $vendor2;
    public $vendor3;

    public $origin1;
    public $origin2;
    public $origin3;
    public $hopeWeight;

    public function rules()
    {
        return [
            [['stockUp','pid','IsLiquid', 'IsPowder', 'isMagnetism', 'IsCharged'], 'integer'],
            [['mapPersons','hopeWeight','picStatus','isVar','vendor1','vendor2','vendor3','developer','devDatetime','updateTime','achieveStatus','GoodsCode',
                'GoodsName','SupplierName', 'AliasCnName','AliasEnName','PackName','description','Season','StoreName','DictionaryName',
                'possessMan2','possessMan1'],'safe'],
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
     * @param array $condition
     *
     * @return ActiveDataProvider
     */
    public function search($params,$condition = [],$unit)
    {


        $query = OaGoodsinfo::find()->joinWith('oa_goods')->orderBy(['pid' => SORT_DESC])->where($condition);
        //返回当前登录用户
        $user = yii::$app->user->identity->username;
        //根据角色 过滤
        $role_sql = yii::$app->db->createCommand("SELECT t2.item_name FROM [user] t1,[auth_assignment] t2 
                    WHERE  t1.id=t2.user_id and
                    username='$user'
                    ");
        $role = $role_sql->queryAll();

        // 返回当前用户管辖下的用户
        $sql = "oa_P_users '{$user}'";
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        $users = [];
        foreach ($result as $user) {
            array_push($users, $user['userName']);
        }
        $role = ArrayHelper::getColumn($role,'item_name');
        //var_dump($unit);exit;
        //var_dump($users);exit;
        //var_dump(in_array('产品开发',$role));exit;
        /*
         * 分模块判断
         *
         */
        if($unit == '产品推荐'){
            if(in_array('部门主管',$role)){
                $query->andWhere(['in', 'oa_goods.developer', $users]);
            }elseif(in_array('eBay销售',$role) ||in_array('SMT销售',$role)||in_array('wish销售',$role)||in_array('Joom销售',$role)){
                $query->andWhere(['in', 'introducer', $users]);
            }
        }elseif($unit == '正向开发'||$unit == '逆向开发'){
            if(in_array('部门主管',$role)){
                $query->andWhere(['in', 'oa_goods.developer', $users]);
            }elseif(in_array('eBay销售',$role) ||in_array('SMT销售',$role)||in_array('wish销售',$role)||in_array('Joom销售',$role)){
                $query->andWhere(['in', 'introducer', $users]);
            }elseif (in_array('产品开发',$role)){
                $query->andWhere(['in', 'oa_goods.developer', $users]);
            }elseif(in_array('产品开发2',$role)){
                $query->andWhere(['in', 'oa_goods.developer', $users]);
            }
        }elseif($unit == '属性信息'){
            if(in_array('部门主管',$role)){
                $query->andWhere(['in', 'oa_goods.developer', $users]);
            }elseif(in_array('eBay销售',$role) ||in_array('SMT销售',$role)||in_array('wish销售',$role)){
                $query->andWhere(['in', 'introducer', $users]);
            }elseif (in_array('产品开发',$role)){
                $query->andWhere(['in', 'oa_goods.developer', $users]);
            }elseif(in_array('产品开发2',$role)){
                $query->andWhere(['in', 'oa_goods.developer', $users]);
            }
        }elseif($unit == '图片信息'){
            if(in_array('部门主管',$role)){
                $query->andWhere(['in', 'oa_goods.developer', $users]);
            }elseif(in_array('eBay销售',$role) ||in_array('SMT销售',$role)||in_array('wish销售',$role)){
                $query->andWhere(['in', 'introducer', $users]);
            }elseif (in_array('产品开发',$role)){
                $query->andWhere(['in', 'oa_goods.developer', $users]);
            }elseif(in_array('产品开发2',$role)){
                $query->andWhere(['in', 'oa_goods.developer', $users]);
            }elseif (in_array('美工',$role)){
                $users = \array_merge($users,\array_map(function ($user) {return $user.'-2';}, $users));
                $query->andWhere(['in', 'possessMan1', $users]);
            }
        }
        //var_dump($query->all());exit;
        // add conditions that should always apply here


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => isset($params['pageSize']) && $params['pageSize'] ? $params['pageSize'] : 6,
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
            'pid' => $this->pid,
            'SupplierName' => $this->SupplierName,
            'PackName'=>$this->PackName,
            'description'=>$this->description,
            'StoreName'=>$this->StoreName,
            'Season'=>$this->Season,
            'IsLiquid' => $this->IsLiquid,
            'isMagnetism' => $this->isMagnetism,
            'IsCharged' => $this->IsCharged,
            'DictionaryName'=>$this->DictionaryName,
            'IsPowder' => $this->IsPowder,
            'isVar' => $this->isVar,
        ]);
        if($this->devDatetime){
            $createDate = explode('/', $this->devDatetime);
            $query->andFilterWhere([
                'and',
                ['>=', 'convert(varchar(10),devDatetime,121)', $createDate[0]],
                ['<=', 'convert(varchar(10),devDatetime,121)', $createDate[1]],
            ]);
        }
        if($this->updateTime){
            $updateDate = explode('/', $this->updateTime);
            $query->andFilterWhere([
                'and',
                ['>=', 'convert(varchar(10),updateTime,121)', $updateDate[0]],
                ['<=', 'convert(varchar(10),updateTime,121)', $updateDate[1]],
            ]);
        }

        $query->andFilterWhere(['like', 'possessMan1', $this->possessMan1]);
        $query->andFilterWhere(['like', 'vendor3', $this->vendor3]);
        $query->andFilterWhere(['like', 'vendor2', $this->vendor2]);
        $query->andFilterWhere(['like', 'vendor1', $this->vendor1]);
        $query->andFilterWhere(['like', 'picStatus', $this->picStatus]);
        $query->andFilterWhere(['like', 'AliasEnName', $this->AliasEnName]);
        $query->andFilterWhere(['like', 'AliasCnName', $this->AliasCnName]);
        $query->andFilterWhere(['like', 'GoodsName', $this->GoodsName]);
        $query->andFilterWhere(['like', 'achieveStatus', $this->achieveStatus]);
        $query->andFilterWhere(['like', 'GoodsCode', $this->GoodsCode]);
        $query->andFilterWhere(['like', 'description', $this->description]);
        $query->andFilterWhere(['like', 'AliasCnName', $this->AliasCnName]);
        $query->andFilterWhere(['like', 'vendor1', $this->vendor1]);
        $query->andFilterWhere(['like', 'oa_goodsInfo.stockUp', $this->stockUp]);
        $query->andFilterWhere(['like', 'oa_goods.developer', $this->developer]);

        return $dataProvider;
    }
}
