<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%user}}`.
 */
class m211112_114730_drop_available_requests_column_from_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->dropColumn("user", "available_requests");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->addColumn("user", "available_requests", $this->integer());
    }
}
