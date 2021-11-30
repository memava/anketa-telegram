<?php
/**
 *
 * crequest-bot 2021
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use app\helpers\CountryHelper;
use app\helpers\KeyboardHelper;
use app\models\Bot;
use app\models\BotCountries;
use app\models\CRequest;
use app\models\User;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class GenericmessageCommand extends UserCommand
{
	protected $name = 'genericmessage';

	protected $description = 'Handle generic message';

	protected $version = '1.1.0';

	public function execute(): ServerResponse
	{
		$text = $this->getMessage()->getText();
		$chat_id = $this->getMessage()->getChat()->getId();

		if($model = CRequest::getActiveRequest($chat_id, $this->getMessage()->getBotUsername())) {
			if($model->isInInput()) {
				return $this->set($model);
			}
		}

		if(User::findIdentityByAccessToken($chat_id, $this->getMessage()->getBotUsername()) && $this->isButton()) {
			return $this->action();
		} else if(!User::findIdentityByAccessToken($chat_id, $this->getMessage()->getBotUsername()) || (User::findIdentityByAccessToken($chat_id, $this->getMessage()->getBotUsername()) && !Bot::checkIsCountryFilled($chat_id, $this->getMessage()->getBotUsername()))) {
			return Bot::startCommand($this->getMessage()->getChat()->getId(), $this->getMessage()->getChat()->getUsername(), $this->getMessage()->getChat()->getFirstName(), $this->getMessage()->getBotUsername(), $this->getMessage()->getText(true));
		} else {
			return Bot::mainMenu($chat_id);
		}
//		return Request::emptyResponse();
	}

	/**
	 * @return bool
	 */
	public function isButton()
	{
		$text = $this->getMessage()->getText();
		if(preg_grep(KeyboardHelper::getRxForAll(), explode("\n", $text))) {
			return true;
		}
		return false;
	}

	/**
	 * @return ServerResponse|void
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public function action()
	{
		$text = $this->getMessage()->getText();
		$chat_id = $this->getMessage()->getChat()->getId();
		Request::deleteMessage(["chat_id" => $chat_id, "message_id" => $this->getMessage()->getMessageId()]);
		switch ($text) {
			case KeyboardHelper::BTN_MY_PROFILE:
				return User::myProfile($chat_id, $this->getMessage()->getBotUsername());
				break;
			case KeyboardHelper::BTN_CREATE_REQUEST:
				return CRequest::newRequest($chat_id, $this->getMessage()->getBotUsername());
				break;
			case KeyboardHelper::BTN_DONATE:
				return Bot::donate($chat_id, $this->getMessage()->getBotUsername());
				break;
			case KeyboardHelper::BTN_MAIN_MENU:
				return Bot::mainMenu($chat_id);
				break;
			case KeyboardHelper::BTN_CHANGE_COUNTRY:
				$countries = BotCountries::findAll(["bot_id" => User::findIdentityByAccessToken($chat_id, $this->getMessage()->getBotUsername())->bot_id]);
				foreach ($countries as $country) {
					$k[] = $country->country;
				}
				$keyboard = CountryHelper::getKeyboardFor($k);
				return $this->replyToChat("Выберите страну", ["reply_markup" => $keyboard]);
				break;
		}
	}

	/**
	 * @param CRequest $model
	 * @return ServerResponse|void
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	private function set(CRequest $model)
	{
		$text = $this->getMessage()->getText();
		$chat_id = $this->getMessage()->getChat()->getId();

		switch ($model->status) {
			case CRequest::STATUS_SELECT_FIO:
				return CRequest::selectFio($chat_id, $text, $this->getMessage()->getBotUsername());
				break;
			case CRequest::STATUS_SELECT_BIRTHDAY:
				return CRequest::selectBirthday($chat_id, $text, $this->getMessage()->getBotUsername());
				break;
			case CRequest::STATUS_SELECT_DATE:
				return CRequest::selectDate($chat_id, $text, $this->getMessage()->getBotUsername());
				break;
			case CRequest::STATUS_SELECT_CITY:
				return CRequest::selectCity($chat_id, $text, $this->getMessage()->getBotUsername());
				break;
            case CRequest::STATUS_SELECT_STATUS:
				return CRequest::selectStatus($chat_id, $text, $this->getMessage()->getBotUsername());
				break;
		}
	}
}