<?php

use yii\db\Migration;

/**
 * Class m211116_234909_add_column_config_table
 */
class m211116_234909_add_column_config_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->insert(\app\models\Config::tableName(), ["variable" => \app\models\Config::VAR_HM_API_KEY, "value" => '']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(\app\models\Config::tableName(), ["variable" => \app\models\Config::VAR_HM_API_KEY]);
    }
}
