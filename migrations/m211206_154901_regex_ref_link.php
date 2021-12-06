<?php

use yii\db\Migration;
use yii\helpers\Console;

/**
 * Class m211206_154901_regex_ref_link
 */
class m211206_154901_regex_ref_link extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $users = \app\models\User::find()->all();
        $c = count($users);
        Console::startProgress(0, $c);
        foreach ($users as $k => $user) {
            $user->ref_link = preg_replace('/[^a-zA-Z0-9]/', '', $user->ref_link);
            if($user->save(false)) {
                Console::updateProgress($k+1, $c);
            }
        }
        Console::endProgress();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
