<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CRequest;

/**
 * CRequestSearch represents the model behind the search form of `app\models\CRequest`.
 */
class CRequestSearch extends CRequest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'bot_id', 'user_id', 'language', 'gender', 'status', 'created_at', 'updated_at'], 'integer'],
            [['unique_id', 'city', 'fio', 'birthday', 'request_date', 'slug'], 'safe'],
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
        $query = CRequest::find();

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
            'bot_id' => $this->bot_id,
            'user_id' => $this->user_id,
            'language' => $this->language,
            'gender' => $this->gender,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'unique_id', $this->unique_id])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'fio', $this->fio])
            ->andFilterWhere(['like', 'birthday', $this->birthday])
            ->andFilterWhere(['like', 'request_date', $this->request_date])
            ->andFilterWhere(['like', 'slug', $this->slug]);

		$dataProvider->sort->defaultOrder = ["id" => SORT_DESC];

		return $dataProvider;
    }
}
