<?php

use yii\db\Migration;

/**
 * Class m211111_154639_add_refs_counter_to_user_table
 */
class m211111_154639_add_refs_counter_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn("user", "ref_counter", $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn("user", "ref_counter");
    }

}
