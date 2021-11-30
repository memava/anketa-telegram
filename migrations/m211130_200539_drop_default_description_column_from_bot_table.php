<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%bot}}`.
 */
class m211130_200539_drop_default_description_column_from_bot_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn("bot", "default_description");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn("bot", "default_description", $this->string());
    }
}
