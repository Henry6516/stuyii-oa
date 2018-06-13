<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use backend\models\AuthItem;

/**
 * This is the model class for table "auth_admin_role".
 *
 * @property int $id
 * @property int $roleId
 * @property string $store
 * @property string $plat
 */
class AuthItemSearch extends AuthItem
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
        ];
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
        $query = AuthItem::find()->where(['type'=>1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => isset($params['pageSize']) && $params['pageSize'] ? $params['pageSize'] : 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'name' => $this->name,
        ]);
        return $dataProvider;
    }
}
