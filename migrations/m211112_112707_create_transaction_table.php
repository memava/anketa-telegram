<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transaction}}`.
 */
class m211112_112707_create_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transaction}}', [
            'id' => $this->primaryKey(),
			'type' => $this->integer(),
			'user_id' => $this->integer(),
			'balance_before' => $this->integer(),
			'balance_after' => $this->integer(),
			'sum' => $this->integer(),
			'currency' => $this->integer(),
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
        $this->dropTable('{{%transaction}}');
    }
}
