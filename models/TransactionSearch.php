<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transaction;

/**
 * TransactionSearch represents the model behind the search form of `app\models\Transaction`.
 */
class TransactionSearch extends Transaction
{
	public $token;
	public $bott;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'bott','token', 'balance_before', 'balance_after', 'sum', 'currency', 'status', 'created_at', 'updated_at', 'payment_system'], 'integer'],
            [["user_id"], "string"]
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
        $query = Transaction::find()->joinWith(['user'])->where(["type" => Transaction::TYPE_PAYMENT]);

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
            'type' => $this->type,
            //'user_id' => $this->user_id,
            'balance_before' => $this->balance_before,
            'balance_after' => $this->balance_after,
            'sum' => $this->sum,
            'currency' => $this->currency,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
			'user.token' => $this->token,
			'user.bot_id' => $this->bott,
			'payment_system' => $this->payment_system,
        ]);

        $query->andFilterWhere(["like", "user.username", $this->user_id]);
        $query->andFilterWhere(["or", ["id" => $this->id, "unique_id" => $this->id]]);

		$dataProvider->sort->defaultOrder = ["id" => SORT_DESC];

        return $dataProvider;
    }
}
