<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%keyboard_button}}`.
 */
class m211110_212429_create_keyboard_button_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%keyboard_button}}', [
            'id' => $this->primaryKey(),
			'keyboard_id' => $this->integer(),
			'name' => $this->string(),
			'action' => $this->string(),
			'status' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%keyboard_button}}');
    }
}
