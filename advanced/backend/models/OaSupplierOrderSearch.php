<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OaSupplierOrder;

/**
 * OaSupplierOrderSearch represents the model behind the search form of `backend\models\OaSupplierOrder`.
 */
class OaSupplierOrderSearch extends OaSupplierOrder
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'totalNumber'], 'integer'],
            [['supplierName', 'goodsName','billNumber','expressNumber','deliveryStatus', 'billStatus', 'purchaser', 'syncTime', 'paymentStatus', 'orderTime', 'createdTime', 'updatedTime'], 'safe'],
            [['amt'], 'number'],
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
        $query = OaSupplierOrder::find();

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
            'syncTime' => $this->syncTime,
            'totalNumber' => $this->totalNumber,
            'amt' => $this->amt,
            'orderTime' => $this->orderTime,
            'updatedTime' => $this->updatedTime,
        ]);

        $query->andFilterWhere(['like', 'supplierName', $this->supplierName])
            ->andFilterWhere(['like', 'billNumber', $this->billNumber])
            ->andFilterWhere(['like', 'billStatus', $this->billStatus])
            ->andFilterWhere(['like', 'goodsName', $this->goodsName])
            ->andFilterWhere(['like', 'purchaser', $this->purchaser])
            ->andFilterWhere(['like', 'expressNumber', $this->expressNumber])
            ->andFilterWhere(['like', 'deliveryStatus', $this->deliveryStatus])
            ->andFilterWhere(['like', 'paymentStatus', $this->paymentStatus]);

        return $dataProvider;
    }
}
