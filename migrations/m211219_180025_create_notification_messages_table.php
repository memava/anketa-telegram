<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notification_messages}}`.
 */
class m211219_180025_create_notification_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notification_messages}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'notification_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%notification_messages}}');
    }
}
