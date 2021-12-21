<?php
/**
 *
 * crequest-bot 2021
 */

namespace app\commands;

use app\helpers\CountryHelper;
use app\helpers\KeyboardHelper;
use app\models\Bot;
use app\models\CRequest;
use app\models\Keyboard;
use app\models\Notification;
use app\models\Transaction;
use app\models\User;
use app\models\Config;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use yii\helpers\Console;

class BotController extends \yii\console\Controller
{

    public function bot()
    {
        $users = User::find()->where(['role' => 1])->all();
        foreach ($users as $user){

            $bot = Bot::find()->where(['id' => $user->bot_id])->one();
            $timeUserAdd = $user->created_at;
            $cRequest = CRequest::find()->where(['user_id' => $user->id])->one();
            $token = $bot['token'];
            $botUsername = $bot['bot_name'];

            $tg = new Telegram("$token","$botUsername");


            if($cRequest && $cRequest['status'] !=  CRequest::STATUS_GENERATING || $cRequest['status'] !=  CRequest::STATUS_ACTIVE){
                if((time() - $timeUserAdd) > 600){
                Request::sendMessage(['text'=> Config::get(Config::VAR_TEXT_NO_CREATE_REQUEST),'chat_id' => $user->token, 'reply_markup' => \Longman\TelegramBot\Entities\Keyboard::remove(), "parse_mode" => "markdown"]);
                }
            }
        }
    }

    public function actionIndex()
    {

    }

    public function actionNotify()
    {
        $notifications = Notification::findAll(["bot_id" => 0]);
        $bots = Bot::find()->all();
        Console::output("Notification start");
        $processedBots = [];
        foreach ($bots as $bot) {
            if($bot->notifications) {
                foreach ($bot->notifications as $notification) {
                    Console::output("Notifications for bot $bot->id, notification: $notification->id");
                    print_r($notification->processAll($processedBots));
                }
                $processedBots[] = $bot->id;
            }
        }
        foreach ($notifications as $notification) {
            Console::output("Default notification $notification->id");
            print_r($notification->processAll($processedBots));
        }
    }

    public function actionTest()
    {
        $t = Transaction::find()->one();
        print_r($t->makeLinkXpay());
    }

}