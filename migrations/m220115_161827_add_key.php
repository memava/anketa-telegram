<?php

use yii\db\Migration;

/**
 * Class m220115_161827_add_key
 */
class m220115_161827_add_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->insert(\app\models\Config::tableName(), [
			"variable" => \app\models\Config::VAR_ENCRYPT_KEY,
			"value" => "",
			"comment" => "Ключ шифрования",
			"type" => "string"
		]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(\app\models\Config::tableName(), ["variable" => \app\models\Config::VAR_ENCRYPT_KEY]);
    }
}
