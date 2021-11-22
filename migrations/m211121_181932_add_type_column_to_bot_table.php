<?php

use app\models\Bot;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bot}}`.
 */
class m211121_181932_add_type_column_to_bot_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bot}}', 'type', $this->integer()->defaultValue(1));
        foreach (Bot::find()->all() as $bot) {
            $bot->type = Bot::TYPE_NORMAL;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bot}}', 'type');
    }
}
