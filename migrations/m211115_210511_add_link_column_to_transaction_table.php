<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%transaction}}`.
 */
class m211115_210511_add_link_column_to_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%transaction}}', 'link', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%transaction}}', 'link');
    }
}
