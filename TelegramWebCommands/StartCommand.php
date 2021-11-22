<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use app\models\Bot;
use app\models\Config;
use app\models\User;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class StartCommand extends \Longman\TelegramBot\Commands\UserCommand
{

    /**
     * @inheritDoc
     */
    public function execute(): ServerResponse
    {
        if(!User::findIdentityByAccessToken($this->getMessage()->getChat()->getId(), $this->getMessage()->getChat()->getUsername())) {
            Bot::registerUser($this->getMessage()->getChat()->getId(), $this->getMessage()->getChat()->getUsername(), $this->getMessage()->getChat()->getFirstName(), $this->getMessage()->getBotUsername(), $this->getMessage()->getText(true), User::ROLE_WEB);
        }
        $kbd = new InlineKeyboard([["text" => "Создать бота", "callback_data" => "/newbot"]]);
        return $this->replyToChat(Config::get(Config::VAR_TEXT_WEB_START), ["reply_markup" => $kbd]);
    }
}