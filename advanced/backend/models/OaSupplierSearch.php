<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OaSupplier;

/**
 * OaSupplierSearch represents the model behind the search form of `backend\models\OaSupplier`.
 */
class OaSupplierSearch extends OaSupplier
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['supplierName', 'contactPerson1', 'phone1', 'contactPerson2', 'phone2', 'address',
                'link1', 'link2', 'link3', 'link4', 'link5', 'link6', 'paymentDays', 'payChannel', 'purchase',
                'createtime', 'updatetime'], 'safe'],
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
        //获取当前登录用户
        $user = Yii::$app->user->identity->username;
        $query = OaSupplier::find();
        if($user != 'admin'){
            $query->andWhere(['purchase' => $user]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'createtime' => $this->createtime,
            'updatetime' => $this->updatetime,
        ]);

        $query->andFilterWhere(['like', 'supplierName', $this->supplierName])
            ->andFilterWhere(['like', 'contactPerson1', $this->contactPerson1])
            ->andFilterWhere(['like', 'phone1', $this->phone1])
            ->andFilterWhere(['like', 'contactPerson2', $this->contactPerson2])
            ->andFilterWhere(['like', 'phone2', $this->phone2])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'link1', $this->link1])
            ->andFilterWhere(['like', 'link2', $this->link2])
            ->andFilterWhere(['like', 'link3', $this->link3])
            ->andFilterWhere(['like', 'link4', $this->link4])
            ->andFilterWhere(['like', 'link5', $this->link5])
            ->andFilterWhere(['like', 'link6', $this->link6])
            ->andFilterWhere(['like', 'paymentDays', $this->paymentDays])
            ->andFilterWhere(['like', 'payChannel', $this->payChannel])
            ->andFilterWhere(['like', 'purchase', $this->purchase]);

        return $dataProvider;
    }
}
