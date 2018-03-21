<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OaDataMine;

/**
 * OaDataMineSearch represents the model behind the search form of `app\models\OaDataMine`.
 */
class OaDataMineSearch extends OaDataMine
{
    /**
     *  varMainImage
     */
    public $varMainImage;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['proId', 'platForm', 'progress', 'creator', 'createTime', 'updateTime'], 'safe'],
            [['varMainImage'], 'safe']
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
        $query = OaDataMine::find();
        $query->joinWith(['oa_data_mine_detail']);
        $query->orderBy(['oa_data_mine.id' => SORT_DESC]);

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
            'createTime' => $this->createTime,
            'updateTime' => $this->updateTime,
        ]);

        $query->andFilterWhere(['like', 'proId', $this->proId])
            ->andFilterWhere(['like', 'platForm', $this->platForm])
            ->andFilterWhere(['like', 'progress', $this->progress])
            ->andFilterWhere(['like', 'creator', $this->creator]);

        return $dataProvider;
    }
}
