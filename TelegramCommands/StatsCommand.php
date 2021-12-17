<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use app\models\Bot;
use app\models\User;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class StatsCommand extends UserCommand
{

	public function execute() : ServerResponse
	{
		if($this->isAdmin()) {
			$bot = Bot::findByBotname($this->getMessage()->getBotUsername());
			$text = "Всего подписчиков: $bot->subscribersCount
Сегодня подписчиков: $bot->subscribersDayCount
Запросов всего: $bot->requestCount
Запросов сегодня: $bot->requestDayCount
Нажатия на оплату всего: $bot->clicksPayCount
Нажатия на оплату за день: $bot->clicksPayDayCount ";
			return $this->replyToChat($text);
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
