<?php

use yii\db\Migration;

/**
 * Class m211118_214157_add_pays
 */
class m211118_214157_add_pays extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->insert(\app\models\Config::tableName(), [
			"variable" => \app\models\Config::VAR_PAYS_PUBLIC_KEY,
			"value" => "",
			"comment" => "PAYS public api key"
		]);
		$this->insert(\app\models\Config::tableName(), [
			"variable" => \app\models\Config::VAR_PAYS_PRIVATE_KEY,
			"value" => "",
			"comment" => "PAYS private api key"
		]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(\app\models\Config::tableName(), [
			"variable" => [\app\models\Config::VAR_PAYS_PUBLIC_KEY, \app\models\Config::VAR_PAYS_PRIVATE_KEY]
		]);
    }
}
