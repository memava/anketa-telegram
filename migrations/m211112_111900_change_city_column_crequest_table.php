<?php

use yii\db\Migration;

/**
 * Class m211112_111900_change_city_column_crequest_table
 */
class m211112_111900_change_city_column_crequest_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->alterColumn("crequest", "city", $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->alterColumn("crequest", "city", $this->string());
    }

}
