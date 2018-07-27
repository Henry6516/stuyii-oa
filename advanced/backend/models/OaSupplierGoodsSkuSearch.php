<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OaSupplierGoodsSku;

/**
 * OaSupplierGoodsSkuSearch represents the model behind the search form of `backend\models\OaSupplierGoodsSku`.
 */
class OaSupplierGoodsSkuSearch extends OaSupplierGoodsSku
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'supplierGoodsId', 'purchaseNumber'], 'integer'],
            [['sku', 'property1', 'property2', 'property3', 'image', 'supplierGoodsSku'], 'safe'],
            [['costPrice', 'purchasePrice', 'weight', 'lowestPrice'], 'number'],
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
        $query = OaSupplierGoodsSku::find();

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
            'supplierGoodsId' => $this->supplierGoodsId,
            'costPrice' => $this->costPrice,
            'purchasePrice' => $this->purchasePrice,
            'weight' => $this->weight,
            'lowestPrice' => $this->lowestPrice,
            'purchaseNumber' => $this->purchaseNumber,
        ]);

        $query->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'property1', $this->property1])
            ->andFilterWhere(['like', 'property2', $this->property2])
            ->andFilterWhere(['like', 'property3', $this->property3])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'supplierGoodsSku', $this->supplierGoodsSku]);

        return $dataProvider;
    }
}
