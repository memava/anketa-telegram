<?php

use yii\db\Migration;

/**
 * Class m211130_020836_add_var_text_no_create_request_to_config_table
 */
class m211130_020836_add_var_text_no_create_request_to_config_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert(\app\models\Config::tableName(), [
            "variable" => \app\models\Config::VAR_TEXT_NO_CREATE_REQUEST,
            "value" => "",
            "comment" => "Текст Если человек подписался на бота, но в течении 10мин не оформил запрос"
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211130_020836_add_var_text_no_create_request_to_config_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211130_020836_add_var_text_no_create_request_to_config_table cannot be reverted.\n";

        return false;
    }
    */
}
