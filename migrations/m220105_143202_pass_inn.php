<?php

use yii\db\Migration;

/**
 * Class m220105_143202_pass_inn
 */
class m220105_143202_pass_inn extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert("config", [
            "variable" => \app\models\Config::VAR_TEXT_STEP_THREE_TWO,
            "value" => "",
            "comment" => "Паспорт текст",
            "type" => \app\models\Config::TYPE_STRING
        ]);
        $this->insert("config", [
            "variable" => \app\models\Config::VAR_TEXT_STEP_FOUR_TWO,
            "value" => "",
            "comment" => "ИНН текст",
            "type" => \app\models\Config::TYPE_STRING
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete("config", ["variable" => [\app\models\Config::VAR_TEXT_STEP_THREE_TWO, \app\models\Config::VAR_TEXT_STEP_FOUR_TWO]]);
    }

}
