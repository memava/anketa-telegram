<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bot;

/**
 * BotSearch represents the model behind the search form of `app\models\Bot`.
 */
class BotSearch extends Bot
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'platform', 'free_requests', 'payment_system', 'created_at', 'updated_at'], 'integer'],
            [['name', 'bot_name', 'token'], 'safe'],
            [['requests_for_ref'], 'number'],
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
        $query = Bot::find();

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
            'platform' => $this->platform,
            'free_requests' => $this->free_requests,
            'requests_for_ref' => $this->requests_for_ref,
            'payment_system' => $this->payment_system,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'bot_name', $this->bot_name])
            ->andFilterWhere(['like', 'token', $this->token]);

        return $dataProvider;
    }
}
