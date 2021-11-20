<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%transaction}}`.
 */
class m211119_112706_add_unique_id_column_to_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%transaction}}', 'unique_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%transaction}}', 'unique_id');
    }
}
