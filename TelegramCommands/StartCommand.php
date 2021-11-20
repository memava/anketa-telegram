<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use app\helpers\CountryHelper;
use app\models\Bot;
use app\models\CRequest;
use app\models\User;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\KeyboardButton;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class StartCommand extends UserCommand
{
	protected $name = 'start';
	protected $description = 'A command for start';
	protected $usage = '/start';
	protected $version = '1.0.0';

	public function execute() : ServerResponse
	{
		return Bot::startCommand($this->getMessage()->getChat()->getId(), $this->getMessage()->getChat()->getUsername(), $this->getMessage()->getChat()->getFirstName(), $this->getMessage()->getBotUsername(), $this->getMessage()->getText(true));
	}

}