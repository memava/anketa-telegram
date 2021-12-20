<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notification}}`.
 */
class m211218_222444_create_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(),
            'text' => $this->string(),
            'type' => $this->integer(),
            'condition_type' => $this->integer(),
            'condition_value' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%notification}}');
    }
}
