<?php

namespace app\models;

use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Yii;
use yii\web\UploadedFile;

/**
 * @property string $message
 * @property UploadedFile $image
 * @property bool $oneMessage
 * @property int $bot
 * @property int $users
 */
class Message extends \yii\base\Model
{
    public $message;
    public $image;
    public $oneMessage = 1;
    public $bot;

    public $offset;
    public $limit;

    public $users = 0;

    /**
     * @return array[]
     */
    public function rules()
    {
        return [
            [["message"], "string"],
            [["limit", "offset", "bot"], "integer"],
            [["message"], "required"],
            [["image"], "file"],
            ["oneMessage", "boolean"]
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels()
    {
        return [
            "message" => "Текст",
            "image" => "Картинка (если нужно)",
            "oneMessage" => "Отправить одним сообщением"
        ];
    }

    /**
     * @return bool
     */
    public function upload()
    {
        if(!$this->image) return true;

        $name = "image" . '.' . $this->image->extension;
        $this->image->saveAs(Yii::getAlias('@app/web/uploads/' . $name));
        $this->image = $name;
        return true;
    }

    /**
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function send()
    {
        //$this->message = str_replace(explode(" ", "* _ { } + !"), ["\*", "\_", "\{", "\}", "\+", "\-", "\!"], $this->message);
        if($this->oneMessage) {
            return $this->sendOneMessage();
        } else {
            return $this->sendMessage();
        }
    }

    /**
     * @return bool
     */
    private function sendOneMessage()
    {
        foreach ($this->getUsers() as $user) {
            if(!$user->bot) continue;

            $tg = new Telegram($user->bot->token, $user->bot->bot_name);
            try {
                if($this->image) {
                    Request::sendPhoto(["chat_id" => $user->token, "caption" => $this->message, "photo" => Yii::getAlias('@app/web/uploads/' . $this->image), "reply_markup" => Keyboard::getMainKeyboard()]);
                } else {
                    $user->sendMessage($this->message, Keyboard::getMainKeyboard());
                }
                $this->users++;
            } catch (TelegramException $e) {

            }
        }
        $this->deletePhoto();
        return true;
    }

    /**
     * @return bool
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function sendMessage()
    {
        foreach ($this->getUsers() as $user) {
            if(!$user->bot) continue;

            $tg = new Telegram($user->bot->token, $user->bot->bot_name);
            try {
                if($this->image) {
                    Request::sendPhoto(["chat_id" => $user->token, "photo" => Yii::getAlias('@app/web/uploads/' . $this->image)]);
                }
                $user->sendMessage($this->message, Keyboard::getMainKeyboard());
                $this->users++;
            } catch (TelegramException $e) {

            }
        }
        $this->deletePhoto();
        return true;
    }

    /**
     * @return bool
     */
    private function deletePhoto()
    {
        if(!$this->image) return true;
        return @unlink(Yii::getAlias('@app/web/uploads/' . $this->image));
    }

    /**
     * @return User[]|array|\yii\db\ActiveRecord[]
     */
    private function getUsers()
    {
        if($this->bot == 0) return User::find()->limit($this->limit)->offset($this->offset)->all();
        return User::find()->where(["bot_id" => $this->bot])->limit($this->limit)->offset($this->offset)->all();
    }
}