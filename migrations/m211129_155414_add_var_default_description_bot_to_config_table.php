<?php

use yii\db\Migration;

/**
 * Class m211129_155414_add_var_default_description_bot_to_config_table
 */
class m211129_155414_add_var_default_description_bot_to_config_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert(\app\models\Config::tableName(), [
            "variable" => \app\models\Config::VAR_DEFAULT_DESCRIPTION_BOTS,
            "value" => "",
            "comment" => "Текст описания бота по умолчанию"
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211129_155414_add_var_default_description_bot_to_config_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211129_155414_add_var_default_description_bot_to_config_table cannot be reverted.\n";

        return false;
    }
    */
}
