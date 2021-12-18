<?php

namespace app\models;

use app\helpers\CountryHelper;
use app\helpers\KeyboardHelper;
use Longman\TelegramBot\Entities\InlineKeyboard;
use phpDocumentor\Reflection\Types\Self_;
use Yii;

/**
 * This is the model class for table "keyboard".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $type
 * @property int|null $status
 */
class Keyboard extends \yii\db\ActiveRecord
{
	const TYPE_DONATE = 1;
	const TYPE_BOTS = 2;
    const TYPE_STATUSES = 3;

	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'keyboard';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
            'status' => 'Status',
        ];
    }

	/**
	 * @param $country
	 * @param int $q
	 * @return array
	 */
	public static function getCityKeyboard($country, $q = 3)
	{
		$kbd = CountryHelper::getCountryCities($country);
		foreach ($kbd as $item) {
			$btn[] = ["text" => $item];
		}
		return array_chunk($btn, $q);
	}

	/**
	 * @param false $donate
	 * @return \Longman\TelegramBot\Entities\Keyboard
	 */
	public static function getMainKeyboard($donate = true)
	{
		if(!$donate) {
			$kbd = new \Longman\TelegramBot\Entities\Keyboard(["text" => KeyboardHelper::BTN_CREATE_REQUEST], ["text" => KeyboardHelper::BTN_MY_PROFILE]);
		} else {
			$kbd = new \Longman\TelegramBot\Entities\Keyboard(["text" => KeyboardHelper::BTN_CREATE_REQUEST], ["text" => KeyboardHelper::BTN_MY_PROFILE], ["text" => KeyboardHelper::BTN_DONATE]);

		}
		$kbd->setResizeKeyboard(true);
		//$kbd->setOneTimeKeyboard(true);
		return $kbd;
	}

	/**
	 * @param $type
	 * @return InlineKeyboard|void
	 */
	public static function getKeyboardFor($type, $botId, $mainMenu = true)
	{
		$buttons = KeyboardButton::findAll(["keyboard_id" => $type, "bot_id" => $botId]);
		$kbd = [];
		if($buttons) {
			foreach ($buttons as $button) {
				$kbd[][0] = ["text" => $button->name, "callback_data" => $button->action];
			}
		} else {
		    if($type == self::TYPE_DONATE) {
                $kbd = self::getDefaultButtonsForDonate();
            } else if($type == self::TYPE_BOTS) {
		        $kbd = self::getButtonsForBot($botId);
            } else if($type == self::TYPE_STATUSES) {
                $kbd = self::getButtonsForStatuses($botId);
            }
		}
		if($mainMenu) {
			$kbd[][] = ["text" => KeyboardHelper::BTN_MAIN_MENU, "callback_data" => "/mainmenu"];
			$kbd[][] = ["text" => KeyboardHelper::BTN_MY_PROFILE, "callback_data" => "/profile"];
		}
		return new InlineKeyboard(...$kbd);
	}

	/**
	 * @return string[]
	 */
	public static function getKeyboardsTypes()
	{
		return [
			self::TYPE_DONATE => "Донат"
		];
	}

    /**
     * @param $lang
     * @return array
     */
    public static function getButtonsForStatuses($lang)
    {
        $kbd = [];
        switch ($lang) {
            case CRequest::LANGUAGE_RU:
                $kbd[][] = ["text" => "Позитивный", "callback_data" => "/selectstatus 1"];
                $kbd[][] = ["text" => "Негативный", "callback_data" => "/selectstatus 0"];
                break;
            case CRequest::LANGUAGE_EN:
                $kbd[][] = ["text" => "Positive", "callback_data" => "/selectstatus 1"];
                $kbd[][] = ["text" => "Negative", "callback_data" => "/selectstatus 0"];
                break;
            case CRequest::LANGUAGE_UA:
                $kbd[][] = ["text" => "Виявлено", "callback_data" => "/selectstatus 1"];
                $kbd[][] = ["text" => "Не виявлено", "callback_data" => "/selectstatus 0"];
                break;
        }
        return $kbd;
    }

	/**
	 * @return array
	 */
	public static function getDefaultButtonsForDonate()
	{
		$buttons = Config::get(Config::VAR_DEFAULT_BUTTONS);
		$ex = explode("\n", trim($buttons));
		$kbd = [];
		foreach ($ex as $btn) {
			$ex1 = explode(";", $btn);
			$text = $ex1[0];
			$action = $ex1[1];
			$kbd[][0] = ["text" => $text, "callback_data" => $action];
		}
		return $kbd;
	}

    /**
     * @param $botId
     */
	public static function getButtonsForBot($userToken)
    {
        $bots = Bot::findAll(["user_id" => $userToken]);
        $kbd = [];
        foreach ($bots as $bot) {
            $kbd[][0] = ["text" => $bot->name, "callback_data" => "/bot ".$bot->id];
        }
        return $kbd;
    }
}
