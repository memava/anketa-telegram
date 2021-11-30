<?php

use yii\db\Migration;

/**
 * Class m211130_194511_add_default_image
 */
class m211130_194511_add_default_image extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert(\app\models\Config::tableName(), [
            "variable" => \app\models\Config::VAR_DEFAULT_IMAGE,
            "value" => "",
            "type" => \app\models\Config::TYPE_FILE,
            "comment" => "Дефолтная картинка для приветствия"
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(\app\models\Config::tableName(), ["variable" => \app\models\Config::VAR_DEFAULT_IMAGE]);
    }
}
