<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%template}}`.
 */
class m211111_144105_create_template_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%template}}', [
            'id' => $this->primaryKey(),
			'country' => $this->integer(),
			'name' => $this->string(),
			'slug' => $this->string(),
			'domain' => $this->string(),
			'template' => $this->string(),
			'data' => $this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%template}}');
    }
}
