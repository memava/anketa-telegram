<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
	public $botname = '';
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'bot_id', 'gender', 'country', 'ref_id', 'role', 'created_at', 'updated_at'], 'integer'],
            [['token', 'username', 'name', 'ref_link', 'botname'], 'safe'],
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
        $query = User::find()->joinWith('bot');

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
            'gender' => $this->gender,
            'country' => $this->country,
            'ref_id' => $this->ref_id,
            'role' => $this->role,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'user.token', $this->token])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'user.name', $this->name])
            ->andFilterWhere(['like', 'bot.name', $this->botname])
            ->andFilterWhere(['like', 'ref_link', $this->ref_link]);

		$dataProvider->sort->attributes["botname"] = [
			"asc" => ["bot.name" => SORT_ASC],
			"desc" => ["bot.name" => SORT_DESC],
		];

        return $dataProvider;
    }
}
