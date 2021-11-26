<?php

use yii\db\Migration;

/**
 * Class m211124_171218_global24key
 */
class m211124_171218_global24key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert(\app\models\Config::tableName(), ["variable" => \app\models\Config::VAR_GLOBAL24_KEY, "value" => "", "comment" => "Global24 walletID"]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(\app\models\Config::tableName(), ["variable" => \app\models\Config::VAR_GLOBAL24_KEY]);
    }

}
