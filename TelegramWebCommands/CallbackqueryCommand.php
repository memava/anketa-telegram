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
        preg_match('/^\/(\w+)(\s+|)(.+)$/', $this->getCallbackQuery()->getData(), $out);
        $chat_id = $this->getCallbackQuery()->getFrom()->getId();
        if($out) {
            $data = $out[3];
            switch ($out[1]) {
                case 'newbot':
                    Bot::newBot($chat_id, $this->getCallbackQuery()->getFrom()->getUsername(), $this->getCallbackQuery()->getFrom()->getFirstName(),$this->getCallbackQuery()->getFrom()->getBotUsername(), "");
                    break;
                case 'accept':
                    Bot::acceptNewBot($data);
                    break;
                case 'bots':
                    Bot::getBots($data);
                    break;
                default:
                    Bot::newBot($chat_id, $this->getCallbackQuery()->getFrom()->getUsername(), $this->getCallbackQuery()->getFrom()->getFirstName(),$this->getCallbackQuery()->getFrom()->getBotUsername(), "");
                    break;
            }
        } else {
            return Bot::newBot($chat_id, $this->getCallbackQuery()->getFrom()->getUsername(), $this->getCallbackQuery()->getFrom()->getFirstName(),$this->getCallbackQuery()->getFrom()->getBotUsername(), "");
        }
    }
}