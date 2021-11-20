<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "keyboard_button".
 *
 * @property int $id
 * @property int|null $keyboard_id
 * @property string|null $name
 * @property string|null $action
 * @property int|null $status
 * @property int|null $bot_id
 */
class KeyboardButton extends \yii\db\ActiveRecord
{
	const STATUS_ENABLE = 1;
	const STATUS_DISABLE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'keyboard_button';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['keyboard_id', 'status', 'bot_id'], 'integer'],
            [['name', 'action'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'keyboard_id' => 'Клавиатура',
            'name' => 'Название',
            'action' => 'Действие',
            'status' => 'Статус',
			'bot_id' => "Бот"
        ];
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBot()
	{
		return $this->hasOne(Bot::class, ["id" => "bot_id"]);
	}

}
