<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%request}}`.
 */
class m211221_191218_add_inn_column_to_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('crequest', 'inn', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('crequest', 'inn');
    }
}
