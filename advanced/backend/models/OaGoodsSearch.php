<?php
namespace backend\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * OaGoodsSearch represents the model behind the search form about `backend\models\OaGoods`.
 */
class OaGoodsSearch extends OaGoods
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['id'], 'integer'],
            [['cate', 'devNum', 'origin1', 'developer', 'introducer',
                'devStatus', 'checkStatus','subCate','vendor1','vendor2','vendor3',
                'origin2','origin3','introReason','approvalNote',
            ], 'string'],
            [['hopeRate','salePrice', 'hopeWeight','hopeMonthProfit','hopeSale','hopeCost','nid'], 'number'],
            [['stockUp','cate','subCate','createDate', 'updateDate',], 'safe'],
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
    public function search($params,$devStatus,$checkStatus,$unit)
    {
        //返回当前登录用户
        $user = yii::$app->user->identity->username;

        //根据角色 过滤
        $role_sql = yii::$app->db->createCommand("SELECT t2.item_name FROM [user] t1,[auth_assignment] t2 
                    WHERE  t1.id=t2.user_id and
                    username='$user'
                    ");
        $role = $role_sql
            ->queryAll();

        // 返回当前用户管辖下的用户
        $sql = "oa_P_users '{$user}'";
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        $users = [];
        foreach ($result as $user) {
            array_push($users, $user['userName']);
        }


        //产品审批状态
        if(!empty($checkStatus)){
            $query = OaGoods::find()->orderBy(['nid' => SORT_DESC])
                ->where(['checkStatus'=>$checkStatus])
                ->andWhere(['<>','checkStatus','已作废'])
                ->andWhere(['in', 'developer', $users]);



        }
        //产品认领状态
        if(!empty($devStatus)){
            $query = OaGoods::find()->orderBy(['nid' => SORT_DESC])
                ->where(['devStatus'=>$devStatus])
                ->andWhere(['<>','checkStatus','已作废'])
            ;
        }

        //已认领产品从推荐消失
        //有推荐人，没作废的产品显示在产品推荐里面。
        if(empty($devStatus) && empty($checkStatus)){
            $query = OaGoods::find()->orderBy(['nid' => SORT_DESC])
                ->where(['<>','introducer',''])
                ->andWhere(['<>','checkStatus','已作废'])
                //->andWhere(['=','checkStatus','未认领'])
            ;
        }

        /*
         * 分模块判断
         *
         */

        if($unit == '产品推荐'){
            //print_r($role[0]['item_name']);
            //print_r($users);exit;
            if(strpos($role[0]['item_name'], '产品开发') !== false){
                $query->andWhere(['OR',['developer' => $users],["ISNULL(developer,'')" => '']]);
            }elseif ($role[0]['item_name']=='美工'){
                $query->andWhere(['in', 'introducer', $users]);
            }
            /*if($role[0]['item_name']=='eBay销售'||$role[0]['item_name']=='SMT销售'||$role[0]['item_name']=='wish销售'){
                $query->andWhere(['in', 'introducer', $users]);
            }elseif ($role[0]['item_name']=='美工'){
                $query->andWhere(['in', 'introducer', $users]);
            }*/
            if(!isset($params['OaGoodsSearch'])){
                $query->andWhere(['checkStatus' => '未认领']);
            }else{
                $query->andWhere(['in', 'checkStatus', ['未认领', '已认领']]);
            }

        }elseif($unit == '正向开发'||$unit == '逆向开发'){
            if($role[0]['item_name']=='部门主管'){
                $query->andWhere(['in', 'oa_goods.developer', $users]);
            }elseif($role[0]['item_name']=='eBay销售'||$role[0]['item_name']=='SMT销售'||$role[0]['item_name']=='wish销售'){
                $query->andWhere(['in', 'introducer', $users]);
            }elseif ($role[0]['item_name']=='产品开发'){
                $query->andWhere(['in', 'oa_goods.developer', $users]);
            }elseif($role[0]['item_name']=='产品开发组长'){
                $query->andWhere(['in', 'oa_goods.developer', $users]);
            }
            //设置显示的数据  默认显示 待审核和待提交数据
            //没有搜索条件，则添加默认显示产品状态条件
            if(!isset($params['OaGoodsSearch'])){
                $query->andWhere(['or',['checkStatus' => '已认领'], ['checkStatus' => '待审批'],['checkStatus' => '待提交']]);
                //$query->andWhere(['checkStatus' => '已认领'])->union(['checkStatus' => '待审批'],true)->union(['checkStatus' => '待提交'],true);
            }
        }

        // add conditions that should always apply here
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
        $query->andFilterWhere(['nid' => $this->nid,]);
        if($this->createDate){
            $createDate = explode('/', $this->createDate);
            $query->andFilterWhere([
                'and',
                ['>=', 'convert(varchar(10),createDate,121)', $createDate[0]],
                ['<=', 'convert(varchar(10),createDate,121)', $createDate[1]],
            ]);
        }
        if($this->updateDate){
            $updateDate = explode('/', $this->updateDate);
            $query->andFilterWhere([
                'and',
                ['>=', 'convert(varchar(10),updateDate,121)', $updateDate[0]],
                ['<=', 'convert(varchar(10),updateDate,121)', $updateDate[1]],
            ]);
        }


        $query->andFilterWhere(['like', 'cate', $this->cate])
            ->andFilterWhere(['like', 'subCate', $this->subCate])
            ->andFilterWhere(['like', 'vendor1', $this->vendor1])
            ->andFilterWhere(['like', 'devNum', $this->devNum])
            ->andFilterWhere(['like', 'origin1', $this->origin1])
            ->andFilterWhere(['like', 'developer', $this->developer])
            ->andFilterWhere(['like', 'introducer', $this->introducer])
            ->andFilterWhere(['like', 'introReason', $this->introReason])
            ->andFilterWhere(['like', 'approvalNote', $this->approvalNote])
            ->andFilterWhere(['like', 'checkStatus', $this->checkStatus])
            ->andFilterWhere(['like', 'stockUp', $this->stockUp]);
        if($this->salePrice){
            $query->andFilterWhere(['and',['>=', 'salePrice', $this->salePrice], ['<', 'salePrice', ceil($this->salePrice)]]);
        }
        if($this->hopeWeight){
            $query->andFilterWhere(['and',['>=', 'hopeWeight', $this->hopeWeight], ['<', 'hopeWeight', ceil($this->hopeWeight )]]);
        }
        if($this->hopeRate){
            $query->andFilterWhere(['and',['>=', 'hopeRate', $this->hopeRate], ['<', 'hopeRate', ceil($this->hopeRate )]]);
        }
        if($this->hopeSale){
            $query->andFilterWhere(['and',['>=', 'hopeSale', $this->hopeSale], ['<', 'hopeSale', ceil($this->hopeSale)]]);
        }
        if($this->hopeCost){
            $query->andFilterWhere(['and',['>=', 'hopeCost', $this->hopeCost], ['<', 'hopeCost', ceil($this->hopeCost)]]);
        }
        if($this->hopeMonthProfit){
            $query->andFilterWhere(['and',['>=', 'hopeMonthProfit', $this->hopeMonthProfit], ['<', 'hopeMonthProfit', ceil($this->hopeMonthProfit)]]);
        }
        return $dataProvider;
    }
}