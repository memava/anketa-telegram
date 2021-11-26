<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%user_log}}`.
 */
class m211124_143040_drop_user_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%user_log}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%user_log}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
