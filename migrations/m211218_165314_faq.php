<?php

use yii\db\Migration;

/**
 * Class m211218_165314_faq
 */
class m211218_165314_faq extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert(\app\models\Config::tableName(), [
            "variable" => \app\models\Config::VAR_TEXT_FAQ,
            "value" => "",
            "comment" => "Частые вопросы",
            "type" => "string"
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(\app\models\Config::tableName(), ["variable" => \app\models\Config::VAR_TEXT_FAQ]);
    }

}
