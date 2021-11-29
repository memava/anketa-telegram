<?php

use yii\db\Migration;

/**
 * Class m211129_182817_add_var_default_link_text_to_config_table
 */
class m211129_182817_add_var_default_link_text_to_config_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert(\app\models\Config::tableName(), [
            "variable" => \app\models\Config::VAR_DEFAULT_LINK_TEXT,
            "value" => "",
            "comment" => "Приглашай друзей и получай бесплатные тесты! {link} текст по умолчанию"
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211129_182817_add_var_default_link_text_to_config_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211129_182817_add_var_default_link_text_to_config_table cannot be reverted.\n";

        return false;
    }
    */
}
