<?php

use yii\db\Migration;

/**
 * Class m211111_135639_change_fio_column_in_crequest_table
 */
class m211111_135639_change_fio_column_in_crequest_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->alterColumn("crequest", "fio", $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->alterColumn("crequest", "fio", $this->string());
    }

}
