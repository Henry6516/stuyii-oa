<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OaJoomWish;

/**
 * OaJoomWishSearch represents the model behind the search form of `app\models\OaJoomWish`.
 */
class OaJoomWishSearch extends OaJoomWish
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nid'], 'integer'],
            [['greater_equal', 'less', 'added_price'], 'number'],
            [['createDate', 'updateDate'], 'safe'],
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
        $query = OaJoomWish::find();

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
            'nid' => $this->nid,
            'greater_equal' => $this->greater_equal,
            'less' => $this->less,
            'added_price' => $this->added_price,
            'createDate' => $this->createDate,
            'updateDate' => $this->updateDate,
        ]);

        return $dataProvider;
    }
}
