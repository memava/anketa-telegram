<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m211110_205940_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
			'bot_id' => $this->integer(),
			'token' => $this->text(),
			'username' => $this->string(),
			'name' => $this->string(),
			'gender' => $this->integer(),
			'country' => $this->integer(),
			'ref_id' => $this->integer(),
			'ref_link' => $this->string(),
			'role' => $this->integer(),
			'available_requests' => $this->integer(),
			'created_at' => $this->integer(),
			'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
