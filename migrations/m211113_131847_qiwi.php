<?php

use yii\db\Migration;

/**
 * Class m211113_131847_qiwi
 */
class m211113_131847_qiwi extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->insert(\app\models\Config::tableName(), [
			"variable" => \app\models\Config::VAR_QIWI_PUBLIC_KEY,
			"value" => ""
		]);

		$this->insert(\app\models\Config::tableName(), [
			"variable" => \app\models\Config::VAR_QIWI_PRIVATE_KEY,
			"value" => ""
		]);

		$this->addPrimaryKey("config-pk", "config", "variable");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropPrimaryKey("config-pk", "config");
        $this->delete(\app\models\Config::tableName(), ["variable" => [\app\models\Config::VAR_QIWI_PUBLIC_KEY, \app\models\Config::VAR_QIWI_PRIVATE_KEY]]);
    }
}
