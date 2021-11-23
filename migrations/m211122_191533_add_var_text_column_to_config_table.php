<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%config}}`.
 */
class m211122_191533_add_var_text_column_to_config_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert(\app\models\Config::tableName(), [
            "variable" => \app\models\Config::VAR_TEXT_WEB_AFTER_CREATE,
            "value" => "",
            "comment" => "Текст после создания бота"
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(\app\models\Config::tableName(), ["variable" => \app\models\Config::VAR_TEXT_WEB_AFTER_CREATE]);
    }
}
