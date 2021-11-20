<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_log}}`.
 */
class m211117_220204_create_user_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_log}}', [
            'id' => $this->primaryKey(),
			'user_id' => $this->integer(),
			'action' => $this->integer(),
			'ip' => $this->string(),
			'created_at' => $this->integer(),
			'updated_at' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_log}}');
    }
}
