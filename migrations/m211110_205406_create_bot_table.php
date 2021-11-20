<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bot}}`.
 */
class m211110_205406_create_bot_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bot}}', [
            'id' => $this->primaryKey(),
			'platform' => $this->integer(),
			'name' => $this->string(),
			'bot_name' => $this->string(),
			'token' => $this->text(),
			'free_requests' => $this->integer(),
			'requests_for_ref' => $this->double(2),
			'payment_system' => $this->integer(),
			'created_at' => $this->integer(),
			'updated_at' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bot}}');
    }
}
