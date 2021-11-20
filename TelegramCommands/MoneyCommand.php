<?php
/**
 *
 * crequest-bot 2021
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use app\models\Bot;
use app\models\User;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class MoneyCommand extends \Longman\TelegramBot\Commands\UserCommand
{

	/**
	 * @inheritDoc
	 */
	public function execute(): ServerResponse
	{
		$chat_id = $this->getMessage()->getFrom()->getId();
		$botName = $this->getMessage()->getBotUsername();

		if($this->isAdmin()) {
			$sum = Bot::findByBotname($botName)->usersSumPaid;
			return $this->replyToChat("Общая сумма оплат: ".$sum);
		}
		return Request::emptyResponse();
	}

	/**
	 * @return bool
	 */
	private function isAdmin()
	{
		$chat_id = $this->getMessage()->getFrom()->getId();
		$botName = $this->getMessage()->getBotUsername();

		$bot = Bot::findByBotname($botName);
		if($u = User::findOne(["bot_id" => $bot->id, "token" => $chat_id])) {
			return $u->isAdmin();
		}
		return false;
	}
}