<?php

use app\models\Config;
use yii\db\Migration;

/**
 * Class m211117_134926_add_actuallink_bot
 */
class m211117_134926_add_actuallink_bot extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn("bot", "reserve_bot", $this->string());
		$this->addColumn("config", "comment", $this->string());
		$this->insert("config", [
			"variable" => Config::VAR_DEFAULT_RESERVE_BOT,
			"value" => "",
			"comment" => "Дефолтный резервный бот"
		]);
		$this->insert("config", [
			"variable" => Config::VAR_TEXT_RESERVE,
			"value" => "",
			"comment" => "Текст для резервного бота ({link})"
		]);
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete("config", ["variable" => [Config::VAR_TEXT_RESERVE, Config::VAR_DEFAULT_RESERVE_BOT]]);
		$this->dropColumn("config", "comment");
		$this->dropColumn("bot", "reserve_bot");
    }

}
