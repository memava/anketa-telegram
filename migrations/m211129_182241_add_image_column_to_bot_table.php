<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bot}}`.
 */
class m211129_182241_add_image_column_to_bot_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bot}}', 'image', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bot}}', 'image');
    }
}
