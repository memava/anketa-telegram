<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bot_countries}}`.
 */
class m211110_211126_create_bot_countries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bot_countries}}', [
            'id' => $this->primaryKey(),
			'bot_id' => $this->integer(),
			'country' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bot_countries}}');
    }
}
