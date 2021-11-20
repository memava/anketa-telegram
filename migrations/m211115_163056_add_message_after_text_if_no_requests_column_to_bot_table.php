<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bot}}`.
 */
class m211115_163056_add_message_after_text_if_no_requests_column_to_bot_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn("bot", "message_after_request_if_no_requests", $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn("bot", "message_after_request_if_no_requests");
    }
}
