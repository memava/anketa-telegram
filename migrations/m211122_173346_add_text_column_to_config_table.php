<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%config}}`.
 */
class m211122_173346_add_text_column_to_config_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert(\app\models\Config::tableName(), [
            "variable" => \app\models\Config::VAR_TEXT_WEB_START,
            "value" => "",
            "comment" => "Бот для вебов старт"
        ]);
        $this->insert(\app\models\Config::tableName(), [
            "variable" => \app\models\Config::VAR_TEXT_WEB_APIKEY,
            "value" => "",
            "comment" => "Бот для вебов ввод апи ключа"
        ]);
        $this->insert(\app\models\Config::tableName(), [
            "variable" => \app\models\Config::VAR_TEXT_WEB_NAME,
            "value" => "",
            "comment" => "Бот для вебов ввод названия бота"
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(\app\models\Config::tableName(), ["variable" => [\app\models\Config::VAR_TEXT_WEB_START, \app\models\Config::VAR_TEXT_WEB_APIKEY, \app\models\Config::VAR_TEXT_WEB_NAME]]);
    }
}
