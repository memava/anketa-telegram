<?php

use yii\db\Migration;

/**
 * Class m211111_171232_change_av_request_column
 */
class m211111_171232_change_av_requests_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->alterColumn("user", "available_requests", $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->alterColumn("user", "available_requests", $this->integer()->defaultValue(0));
	}
}
