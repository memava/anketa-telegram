<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_action}}`.
 */
class m211110_213057_create_user_action_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_action}}', [
            'id' => $this->primaryKey(),
			'bot_id' => $this->integer(),
			'user_id' => $this->integer(),
			'type' => $this->integer(),
			'created_at' => $this->integer(),
			'updated_at' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_action}}');
    }
}
