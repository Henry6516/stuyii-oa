<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OaSupplierGoods;

/**
 * OaSupplierGoodsSearch represents the model behind the search form of `backend\models\OaSupplierGoods`.
 */
class OaSupplierGoodsSearch extends OaSupplierGoods
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['supplier', 'purchaser', 'goodsCode', 'goodsName', 'supplierGoodsCode', 'createdTime', 'updatedTime'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = OaSupplierGoods::find();

        // add conditions that should always apply here
        $user = Yii::$app->user->identity->username;
        if ($user !== 'admin') {
            $query->andWhere(['purchaser'=>$user]);
        }

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
            'createdTime' => $this->createdTime,
            'updatedTime' => $this->updatedTime,
        ]);

        $query->andFilterWhere(['like', 'supplier', $this->supplier])
            ->andFilterWhere(['like', 'purchaser', $this->purchaser])
            ->andFilterWhere(['like', 'goodsCode', $this->goodsCode])
            ->andFilterWhere(['like', 'goodsName', $this->goodsName])
            ->andFilterWhere(['like', 'supplierGoodsCode', $this->supplierGoodsCode]);

        return $dataProvider;
    }
}
