<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%template}}`.
 */
class m211113_120640_add_language_column_to_clinic_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%template}}', 'language', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%template}}', 'language');
    }
}
