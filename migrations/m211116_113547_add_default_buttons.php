<?php

use yii\db\Migration;

/**
 * Class m211116_113547_add_default_buttons
 */
class m211116_113547_add_default_buttons extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->insert("config", ["variable" => \app\models\Config::VAR_DEFAULT_BUTTONS]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->delete("config", ["variable" => \app\models\Config::VAR_DEFAULT_BUTTONS]);
    }

}
