<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OaJoomSuffix;

/**
 * OaJoomSuffixSearch represents the model behind the search form of `backend\models\OaJoomSuffix`.
 */
class OaJoomSuffixSearch extends OaJoomSuffix
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nid'], 'integer'],
            [['joomName', 'imgCode', 'mainImg', 'skuCode'], 'safe'],
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
        $query = OaJoomSuffix::find();

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
        ]);

        $query->andFilterWhere(['like', 'joomName', $this->joomName])
            ->andFilterWhere(['like', 'imgCode', $this->imgCode])
            ->andFilterWhere(['like', 'mainImg', $this->mainImg])
            ->andFilterWhere(['like', 'skuCode', $this->skuCode]);

        return $dataProvider;
    }
}
