<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use app\models\Bot;
use app\models\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class MybotsCommand extends \Longman\TelegramBot\Commands\UserCommand
{

    /**
     * @inheritDoc
     */
    public function execute(): ServerResponse
    {
        $bot = Bot::findByBotname($this->getMessage()->getBotUsername());
        return $this->replyToChat("Ваши боты", ["reply_markup" => Keyboard::getKeyboardFor(Keyboard::TYPE_BOTS, $bot->id)]);
    }
}