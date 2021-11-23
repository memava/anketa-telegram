<?php

use yii\db\Migration;

/**
 * Class m211123_205139_admin_password_var
 */
class m211123_205139_admin_password_var extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert("config", ["variable" => \app\models\Config::VAR_ADMIN_PASSWORD, "value" => "admin", "comment" => "Админ пароль"]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete("config", ["variable" => \app\models\Config::VAR_ADMIN_PASSWORD]);
    }
}
