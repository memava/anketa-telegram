<?php

use yii\db\Migration;

/**
 * Class m211118_215947_changes_in_transactions
 */
class m211118_215947_changes_in_transactions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->delete(\app\models\Transaction::tableName(), ["type" => \app\models\Transaction::TYPE_PAYMENT]);
		$this->dropColumn(\app\models\Transaction::tableName(), "sum");
		$this->addColumn(\app\models\Transaction::tableName(), "sum_uah", $this->integer());
		$this->addColumn(\app\models\Transaction::tableName(), "sum_rub", $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(\app\models\Transaction::tableName(), "sum_rub");
        $this->dropColumn(\app\models\Transaction::tableName(), "sum_uah");
		$this->addColumn(\app\models\Transaction::tableName(), "sum", $this->integer());
	}

}
