<?php

use yii\db\Migration;

/**
 * Class m211130_163221_addconfig
 */
class m211130_163221_addconfig extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert("config", [
            "variable" => \app\models\Config::VAR_TEXT_STEP_ONE_ONE,
            "value" => "",
            "comment" => ""
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete("config", ["variable" => \app\models\Config::VAR_TEXT_STEP_ONE_ONE]);
    }
}
