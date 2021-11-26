<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use app\models\Bot;
use app\models\BotCountries;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class ChangecountriesCommand extends \Longman\TelegramBot\Commands\UserCommand
{

    /**
     * @inheritDoc
     */
    public function execute(): ServerResponse
    {
        $text = $this->getMessage()->getText(true);
        $token = $this->getMessage()->getFrom()->getId();

        $ex = explode(" ", $text);
        $botId = $ex[0];
        unset($ex[0]);
        $model = Bot::findOne($botId);

        if($model->user_id != $token) return Request::emptyResponse();

        BotCountries::deleteAll(["bot_id" => $botId]);
        foreach ($ex as $c) {
            $m = new BotCountries();
            $m->bot_id = $botId;
            $m->country = $c;
            $m->save(false);
        }

        $this->replyToChat("Страны успешно изменены");
        return Bot::getBot($botId);
    }
}