<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%keyboard_button}}`.
 */
class m211115_225630_add_bot_id_column_to_keyboard_button_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%keyboard_button}}', 'bot_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%keyboard_button}}', 'bot_id');
    }
}
