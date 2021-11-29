<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bot}}`.
 */
class m211129_132600_add_default_description_column_to_bot_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bot}}', 'default_description', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bot}}', 'default_description');
    }
}
