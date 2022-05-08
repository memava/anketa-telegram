<?php

use yii\db\Migration;

/**
 * Class m220105_120111_add_country_to_config
 */
class m220105_120111_add_country_to_config extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('config', 'country_id', $this->integer());
        $this->addColumn('config', 'is_message', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('config', 'country_id');
        $this->dropColumn('config', 'is_message');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220105_120111_add_country_to_config cannot be reverted.\n";

        return false;
    }
    */
}
