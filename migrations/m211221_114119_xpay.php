<?php

use app\models\Config;
use yii\db\Migration;

/**
 * Class m211221_114119_xpay
 */
class m211221_114119_xpay extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert(Config::tableName(), [
            "variable" => Config::VAR_XPAY_OUR_PRIVATE_KEY,
            "value" => "",
            "comment" => "Наш приватный ключ для XPAY",
            "type" => Config::TYPE_STRING
        ]);
        $this->insert(Config::tableName(), [
            "variable" => Config::VAR_XPAY_PARTNER_TOKEN,
            "value" => "",
            "comment" => "Партнер-токен от XPAY",
            "type" => Config::TYPE_STRING
        ]);
        $this->insert(Config::tableName(), [
            "variable" => Config::VAR_XPAY_THEIR_PUBLIC_KEY,
            "value" => "",
            "comment" => "Публичный ключ XPAY",
            "type" => Config::TYPE_STRING
        ]);
        $this->insert(Config::tableName(), [
            "variable" => Config::VAR_XPAY_EMAIL_FOR_PAYMENT,
            "value" => "",
            "comment" => "Email для платежа XPAY",
            "type" => Config::TYPE_STRING
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(Config::tableName(), ["variable" => [Config::VAR_XPAY_EMAIL_FOR_PAYMENT, Config::VAR_XPAY_THEIR_PUBLIC_KEY, Config::VAR_XPAY_PARTNER_TOKEN, Config::VAR_XPAY_OUR_PRIVATE_KEY]]);
    }

}
