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

class AddtestCommand extends \Longman\TelegramBot\Commands\UserCommand
{

	/**
	 * @inheritDoc
	 */
	public function execute(): ServerResponse
	{
		$chat_id = $this->getMessage()->getFrom()->getId();
		$botName = $this->getMessage()->getBotUsername();

		if($this->isAdmin()) {
			$ex = explode(" ", $this->getMessage()->getText(true));
			if($ex && isset($ex[0]) && isset($ex[1]) && $u = User::findIdentityByAccessToken($ex[0], $botName)) {
				$u->changeBalance($ex[1]);
				return $this->replyToChat("Баланс у пользователя @{$u->username} : {$u->name}({$u->token}) обновлен!\nТекущий баланс: {$u->available_requests}");
			}
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