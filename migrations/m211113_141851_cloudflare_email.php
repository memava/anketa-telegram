<?php

use yii\db\Migration;

/**
 * Class m211113_141851_cloudflare_email
 */
class m211113_141851_cloudflare_email extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->insert("config", [
			"variable" => \app\models\Config::VAR_CLOUDFLARE_EMAIL,
			"value" => ""
		]);
		$this->insert("config", [
			"variable" => \app\models\Config::VAR_CLOUDFLARE_KEY,
			"value" => ""
		]);
		$this->insert("config", [
			"variable" => \app\models\Config::VAR_CLOUDFLARE_ZONE,
			"value" => ""
		]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete("config", ["variable" => [
			\app\models\Config::VAR_CLOUDFLARE_KEY,
			\app\models\Config::VAR_CLOUDFLARE_EMAIL,
			\app\models\Config::VAR_CLOUDFLARE_ZONE,
		]]);
    }
}
