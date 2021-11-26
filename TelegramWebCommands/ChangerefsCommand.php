<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use app\models\Bot;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class ChangerefsCommand extends \Longman\TelegramBot\Commands\UserCommand
{

    /**
     * @inheritDoc
     */
    public function execute(): ServerResponse
    {
        $text = $this->getMessage()->getText(true);
        $token = $this->getMessage()->getFrom()->getId();

        $botId = explode(" ", $text)[0];
        $count = explode(" ", $text)[1];

        $model = Bot::findOne($botId);
        if($model && $model->user_id == $token) {
            $model->requests_for_ref = $count;
            $model->save(false);
        }
        $this->replyToChat("Кол-во рефов на $count");
        return Bot::getBot($botId);
    }
}