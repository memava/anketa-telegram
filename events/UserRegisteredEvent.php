<?php
/**
 *
 * crequest-bot 2021
 */

namespace app\events;

use app\models\Bot;
use app\models\User;
use Longman\TelegramBot\Request;

/**
 * @property User $user
 * @property-read User $ref;
 * @property-read Bot $bot;
 */
class UserRegisteredEvent extends \yii\base\Event
{
	/**
	 * @var User
	 */
	public $user;

	/**
	 * @return bool
	 */
	public function isRef()
	{
		return (bool) $this->user->ref_id;
	}

	/**
	 * @return User
	 */
	public function getRef()
	{
		return $this->user->ref;
	}

	/**
	 * @return \app\models\Bot
	 */
	public function getBot()
	{
		return $this->user->bot;
	}
}