<?php

use yii\db\Migration;

/**
 * Class m211218_232527_delete_bot_id_column_from_user_action_table
 */
class m211218_232527_delete_bot_id_column_from_user_action_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn("user_action", "bot_id");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn("user_action", "bot_id");
    }

}
