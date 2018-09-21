<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OaSupplierOrder;

/**
 * OaSupplierOrderSearch represents the model behind the search form of `backend\models\OaSupplierOrder`.
 */
class OaSupplierOrderPaymentSearch extends OaSupplierOrderPaymentDetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paymentStatus', 'billNumber','img', 'comment','requestTime', 'paymentTime'], 'safe'],
            [['requestAmt', 'paymentAmt', 'unpaidAmt'], 'number'],
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
        $query = OaSupplierOrderPaymentDetail::find();

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
            'requestAmt' => $this->requestAmt,
            'paymentAmt' => $this->paymentAmt,
            'unpaidAmt' => $this->unpaidAmt,
            'requestTime' => $this->requestTime,
            'paymentTime' => $this->paymentTime,
        ]);

        $query->andFilterWhere(['like', 'billNumber', $this->billNumber])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'paymentStatus', $this->paymentStatus]);

        return $dataProvider;
    }
}
