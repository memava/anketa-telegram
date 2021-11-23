<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%transaction}}`.
 */
class m211123_132610_add_payment_system_column_to_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%transaction}}', 'payment_system', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%transaction}}', 'payment_system');
    }
}
