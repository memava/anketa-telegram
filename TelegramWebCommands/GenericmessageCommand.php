<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use app\models\Bot;
use app\models\CRequest;
use app\models\User;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class GenericmessageCommand extends \Longman\TelegramBot\Commands\UserCommand
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

//        if(User::findIdentityByAccessToken($chat_id, $this->getMessage()->getBotUsername()) && $this->isButton()) {
//            return $this->action();
//        } else if(!User::findIdentityByAccessToken($chat_id, $this->getMessage()->getBotUsername()) || (User::findIdentityByAccessToken($chat_id, $this->getMessage()->getBotUsername()) && !Bot::checkIsCountryFilled($chat_id, $this->getMessage()->getBotUsername()))) {
//            return Bot::startCommand($this->getMessage()->getChat()->getId(), $this->getMessage()->getChat()->getUsername(), $this->getMessage()->getChat()->getFirstName(), $this->getMessage()->getBotUsername(), $this->getMessage()->getText(true));
//        } else {
//            return Bot::mainMenu($chat_id);
//        }
//		return Request::emptyResponse();
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
            case CRequest::STATUS_WEB_API_KEY:
                return CRequest::selectApiKey($chat_id, $text, $this->getMessage()->getBotUsername());
                break;
            case CRequest::STATUS_WEB_NAME:
                return CRequest::selectName($chat_id, $text, $this->getMessage()->getBotUsername());
                break;
        }
    }
}