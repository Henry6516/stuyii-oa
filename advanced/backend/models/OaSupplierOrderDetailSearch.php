<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OaSupplierOrderDetail;

/**
 * OaSupplierOrderDetailSearch represents the model behind the search form of `backend\models\OaSupplierOrderDetail`.
 */
class OaSupplierOrderDetailSearch extends OaSupplierOrderDetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'orderId', 'purchaseNumber', 'deliveryNumber'], 'integer'],
            [['sku', 'image','goodsName', 'supplierGoodsSku', 'goodsName', 'property1', 'property2', 'property3'], 'safe'],
            [['purchasePrice'], 'number'],
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
        $query = OaSupplierOrderDetail::find();

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
            'orderId' => $this->orderId,
            'purchaseNumber' => $this->purchaseNumber,
            'goodsName' => $this->goodsName,
            'purchasePrice' => $this->purchasePrice,
            'deliveryNumber' => $this->deliveryNumber,
        ]);

        $query->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'supplierGoodsSku', $this->supplierGoodsSku])
            ->andFilterWhere(['like', 'goodsName', $this->goodsName])
            ->andFilterWhere(['like', 'property1', $this->property1])
            ->andFilterWhere(['like', 'property2', $this->property2])
            ->andFilterWhere(['like', 'property3', $this->property3]);

        return $dataProvider;
    }
}
