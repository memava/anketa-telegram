<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bot}}`.
 */
class m211116_235938_add_column_to_bot_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn("bot", "request_counter", $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn("bot", "request_counter");
    }
}
