<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%config}}`.
 */
class m211113_124126_create_config_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%config}}', [
			'variable' => $this->string(),
			'value' => $this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%config}}');
    }
}
