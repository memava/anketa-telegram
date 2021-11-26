<?php
/**
 *
 * crequest-bot 2021
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use app\models\Bot;
use app\models\Transaction;
use app\models\User;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use yii\helpers\Url;

class ChecksCommand extends \Longman\TelegramBot\Commands\UserCommand
{

	/**
	 * @inheritDoc
	 */
	public function execute(): ServerResponse
	{
		$chat_id = $this->getMessage()->getFrom()->getId();
		$botName = $this->getMessage()->getBotUsername();

		if($this->isAdmin()) {
			$limit = $this->getMessage()->getText(true);
			$transactions = Bot::findByBotname($botName)->checks(trim($limit));
			if($transactions) {
				$text = '';
				foreach ($transactions as $transaction) {
					/**
					 * @var Transaction $transaction
					 */
					$url = Url::to(["user/view", "id" => $transaction->user->id], true);
					$text .= "\n\nПлательщик: @{$transaction->user->username} {$transaction->user->name} ([{$transaction->user->token}]({$url}))".
						"\nСумма: {$transaction->sum}".
						"\nНачислено: {$transaction->amount}".
						"\n[профиль](tg://user?id={$transaction->user->token})";
				}
				$text = str_replace(explode(" ", "* _ { } +"), ["\*", "\_", "\{", "\}", "\+"], $text);
				return $this->replyToChat($text, ["parse_mode" => "markdown"]);
			} else {
				return $this->replyToChat("Нет транзакций!");
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