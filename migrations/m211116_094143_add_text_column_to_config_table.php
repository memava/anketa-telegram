<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%config}}`.
 */
class m211116_094143_add_text_column_to_config_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->insert(\app\models\Config::tableName(), [
			"variable" => \app\models\Config::VAR_TEXT_STEP_ONE,
			"value" => ""
		]);
		$this->insert(\app\models\Config::tableName(), [
			"variable" => \app\models\Config::VAR_TEXT_STEP_TWO,
			"value" => ""
		]);
		$this->insert(\app\models\Config::tableName(), [
			"variable" => \app\models\Config::VAR_TEXT_STEP_THREE,
			"value" => ""
		]);
		$this->insert(\app\models\Config::tableName(), [
			"variable" => \app\models\Config::VAR_TEXT_STEP_FOUR,
			"value" => ""
		]);
		$this->insert(\app\models\Config::tableName(), [
			"variable" => \app\models\Config::VAR_TEXT_STEP_FIVE,
			"value" => ""
		]);
		$this->insert(\app\models\Config::tableName(), [
			"variable" => \app\models\Config::VAR_TEXT_STEP_SIX,
			"value" => ""
		]);
		$this->insert(\app\models\Config::tableName(), [
			"variable" => \app\models\Config::VAR_TEXT_STEP_SEVEN,
			"value" => ""
		]);
		$this->insert(\app\models\Config::tableName(), [
			"variable" => \app\models\Config::VAR_TEXT_BEFORE_MAKE,
			"value" => ""
		]);
		$this->insert(\app\models\Config::tableName(), [
			"variable" => \app\models\Config::VAR_TEXT_DONATE,
			"value" => ""
		]);
		$this->insert(\app\models\Config::tableName(), [
			"variable" => \app\models\Config::VAR_TEXT_NO_TEMPLATES,
			"value" => ""
		]);
		$this->insert(\app\models\Config::tableName(), [
			"variable" => \app\models\Config::VAR_TEXT_AFTER_MAKE,
			"value" => ""
		]);
		$this->insert(\app\models\Config::tableName(), [
			"variable" => \app\models\Config::VAR_TEXT_NO_REQUESTS,
			"value" => ""
		]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->delete("config", ["variable" => [
			\app\models\Config::VAR_TEXT_STEP_ONE,
			\app\models\Config::VAR_TEXT_STEP_TWO,
			\app\models\Config::VAR_TEXT_STEP_THREE,
			\app\models\Config::VAR_TEXT_STEP_FOUR,
			\app\models\Config::VAR_TEXT_STEP_FIVE,
			\app\models\Config::VAR_TEXT_STEP_SIX,
			\app\models\Config::VAR_TEXT_STEP_SEVEN,
			\app\models\Config::VAR_TEXT_BEFORE_MAKE,
			\app\models\Config::VAR_TEXT_AFTER_MAKE,
			\app\models\Config::VAR_TEXT_DONATE,
			\app\models\Config::VAR_TEXT_NO_TEMPLATES,
			\app\models\Config::VAR_TEXT_NO_REQUESTS,
		]]);
    }
}
