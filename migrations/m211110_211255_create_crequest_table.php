<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%crequest}}`.
 */
class m211110_211255_create_crequest_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%crequest}}', [
            'id' => $this->primaryKey(),
			'bot_id' => $this->integer(),
			'user_id' => $this->integer(),
			'unique_id' => $this->string(),
			'city' => $this->integer(),
			'language' => $this->integer(),
			'fio' => $this->integer(),
			'gender' => $this->integer(),
			'birthday' => $this->string(),
			'request_date' => $this->string(),
			'slug' => $this->string(),
			'status' => $this->integer(),
			'created_at' => $this->integer(),
			'updated_at' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%crequest}}');
    }
}
