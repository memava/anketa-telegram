<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bot_countries".
 *
 * @property int $id
 * @property int|null $bot_id
 * @property int|null $country
 */
class BotCountries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bot_countries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bot_id', 'country'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bot_id' => 'Bot ID',
            'country' => 'Country',
        ];
    }

	/**
	 * @param bool $runValidation
	 * @param null $attributeNames
	 * @return bool
	 */
	public function save($runValidation = true, $attributeNames = null)
	{
		if(!self::findOne(["bot_id" => $this->bot_id, "country" => $this->country])) {
			return parent::save($runValidation, $attributeNames);
		}
		return true;
	}
}
