<?php

/**
 * This file is part of the PHP Telegram Bot example-bot package.
 * https://github.com/php-telegram-bot/example-bot/
 *
 * (c) PHP Telegram Bot Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Callback query command
 *
 * This command handles all callback queries sent via inline keyboard buttons.
 *
 * @see InlinekeyboardCommand.php
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use app\models\Bot;
use app\models\CRequest;
use app\models\User;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class CallbackqueryCommand extends SystemCommand
{
	/**
	 * @var string
	 */
	protected $name = 'callbackquery';

	/**
	 * @var string
	 */
	protected $description = 'Handle the callback query';

	/**
	 * @var string
	 */
	protected $version = '1.2.0';

	/**
	 * Main command execution
	 *
	 * @return ServerResponse
	 * @throws \Exception
	 */
	public function execute(): ServerResponse
	{
		// Callback query data can be fetched and handled accordingly.
		$callback_query = $this->getCallbackQuery();
		$callback_data  = $callback_query->getData();

		$this->execCommand();

		return $callback_query->answer([
			'text'       => "Успех!",
			'show_alert' => false, // Randomly show (or not) as an alert.
			'cache_time' => 5,
		]);
	}

	/**
	 * @return string
	 */
	private function execCommand()
	{
		preg_match('/^\/(\w+)(\s+|)(.+|)$/', $this->getCallbackQuery()->getData(), $out);
		$chat_id = $this->getCallbackQuery()->getFrom()->getId();
		if($out) {
			$data = $out[3];
			switch ($out[1]) {
				case 'selectcountry':
					User::selectCountry($chat_id, $data, $this->getCallbackQuery()->getBotUsername());
					break;
				case 'selectlanguage':
					CRequest::selectLanguage($chat_id, $data, $this->getCallbackQuery()->getBotUsername());
					break;
				case 'selectgender':
					CRequest::selectGender($chat_id, $data, $this->getCallbackQuery()->getBotUsername());
					break;
				case 'generate':
					CRequest::generate($chat_id, $data);
					break;
				case 'newrequest':
					CRequest::newRequest($chat_id, $this->getCallbackQuery()->getBotUsername());
					break;
                case 'selectstatus':
					CRequest::selectStatus($chat_id, $data, $this->getCallbackQuery()->getBotUsername());
					break;
				case 'donate':
					Bot::payment($chat_id, $data, $this->getCallbackQuery()->getBotUsername());
					break;
                case 'profile':
					User::myProfile($chat_id, $this->getCallbackQuery()->getBotUsername());
					break;
				default:
                    Request::sendMessage(["chat_id" => $chat_id, "text" => implode(' ', $out)]);
                    CRequest::newRequest($chat_id, $this->getCallbackQuery()->getBotUsername());
					break;
			}
		} else {
            CRequest::newRequest($chat_id, $this->getCallbackQuery()->getBotUsername());
		}
	}
}