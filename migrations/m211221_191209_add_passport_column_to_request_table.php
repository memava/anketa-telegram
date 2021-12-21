<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%request}}`.
 */
class m211221_191209_add_passport_column_to_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('crequest', 'passport', $this->string());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('crequest', 'passport');
    }
}
