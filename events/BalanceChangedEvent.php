<?php
/**
 *
 * crequest-bot 2021
 */

namespace app\events;

use app\models\Transaction;
use app\models\User;
use Longman\TelegramBot\Telegram;
use yii\base\Event;

/**
 * @property User $user
 * @property float|int $oldBalance
 * @property float|int $newBalance
 * @property float|int $type
 * @property float|int $sum
 * @property-read int $count
 */
class BalanceChangedEvent extends Event
{
	public $user;

	public $oldBalance;
	public $newBalance;

	public $type;
	public $sum;

	/**
	 * @return int
	 */
	public function diff()
	{
		return $this->newBalance - $this->oldBalance;
	}

	/**
	 * @return bool
	 */
	public function isIncrease()
	{
		return $this->diff() > 0;
	}

	/**
	 * @return int
	 */
	public function getCount()
	{
		return abs($this->diff());
	}

	/**
	 * @return bool
	 */
	public function isExternalPayment()
	{
		return $this->type == Transaction::TYPE_PAYMENT;
	}
}