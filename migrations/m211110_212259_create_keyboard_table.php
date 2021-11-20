<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%keyboard}}`.
 */
class m211110_212259_create_keyboard_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%keyboard}}', [
            'id' => $this->primaryKey(),
			'name' => $this->string(),
			'type' => $this->integer(),
			'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%keyboard}}');
    }
}
