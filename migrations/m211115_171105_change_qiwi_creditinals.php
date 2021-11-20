<?php

use yii\db\Migration;

/**
 * Class m211115_171105_change_qiwi_creditinals
 */
class m211115_171105_change_qiwi_creditinals extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->update("config", ["value" => ""], ["variable" => \app\models\Config::VAR_QIWI_PRIVATE_KEY]);
		$this->update("config", ["value" => ""], ["variable" => \app\models\Config::VAR_QIWI_PUBLIC_KEY]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->update("config", ["value" => ""], ["variable" => \app\models\Config::VAR_QIWI_PRIVATE_KEY]);
		$this->update("config", ["value" => ""], ["variable" => \app\models\Config::VAR_QIWI_PUBLIC_KEY]);
	}

}
