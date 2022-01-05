<?php

use yii\db\Migration;

/**
 * Class m220105_190330_change_config_key_primary
 */
class m220105_190330_change_config_key_primary extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropPrimaryKey('PRIMARY', 'config');
        $this->addPrimaryKey('primary_key', 'config', ['variable', 'country_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220105_190330_change_config_key_primary cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220105_190330_change_config_key_primary cannot be reverted.\n";

        return false;
    }
    */
}
