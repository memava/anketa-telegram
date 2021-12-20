<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%notification}}`.
 */
class m211219_182823_add_bot_id_column_to_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%notification}}', 'bot_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%notification}}', 'bot_id');
    }
}
