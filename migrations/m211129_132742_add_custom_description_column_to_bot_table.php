<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bot}}`.
 */
class m211129_132742_add_custom_description_column_to_bot_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bot}}', 'custom_description', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bot}}', 'custom_description');
    }
}
