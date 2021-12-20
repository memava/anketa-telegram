<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_action".
 *
 * @property int $id
 * @property int|null $bot_id
 * @property int|null $user_id
 * @property int|null $type
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class UserAction extends \yii\db\ActiveRecord
{
	const TYPE_CLICK_ON_DONATE = 1;

	/**
	 * @return array
	 */
	public function behaviors()
	{
		return [
			TimestampBehavior::class
		];
	}

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_action';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @param $user_id
     * @param $type
     */
    public static function createInline($user_id, $type)
    {
        $model = new self();
        $model->user_id = $user_id;
        $model->type = $type;
        $model->save(false);
    }
}
