<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "notification".
 *
 * @property int $id
 * @property int|null $bot_id
 * @property string|null $text
 * @property int|null $type
 * @property int|null $condition_type
 * @property int|null $condition_value
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property User $user
 */
class Notification extends \yii\db\ActiveRecord
{
    const TYPE_START = 1; // запись после команды /start

    /**
     * После отправки пользователю кнопок оплаты (транзакция создана),
     * делать запись в базу с указанием даты+времени Х. Х=текущая дата + 24 часа.
     * Если такая запись по пользователю уже существует, то перезаписывать ее.
     * Когда пользователь совершил любую оплату (пришло уведомление об оплате), удалять из таблицы напоминаний запись этого пользователя.
     * Когда время Х наступило, отправлять пользователю напоминание (текст напоминания вывести в админку в конфиг)
     */
    const TYPE_PAY = 2;

    /**
     * После отправки пользователю кнопок с выбором Пакетов,
     * делать запись в базу с указанием даты+времени Х. Х=текущая дата + 24 часа.
     * Если такая запись по пользователю уже существует, то перезаписывать ее.
     * Когда пользователь нажал на кнопку с любым Пакетом (создалась транзакция),
     * удалять из таблицы напоминаний запись этого пользователя.
     * Когда время Х наступило, отправлять пользователю напоминание (текст напоминания вывести в админку в конфиг)
     */
    const TYPE_WASTE = 3;

    const CONDITION_MORE = 1;
    const CONDITION_LESS = 2;

    private $_time;
    private $_exclude;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'condition_type', 'condition_value', 'created_at', 'updated_at', 'bot_id'], 'integer'],
            [['text'], 'string', 'max' => 255],
            [["condition_value"], "integer", "min" => 600]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bot_id' => "Бот",
            'text' => 'Текст',
            'type' => 'Тип',
            'condition_type' => 'Тип условия',
            'condition_value' => 'Условие',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return string[]
     */
    public static function getTypes()
    {
        return [
            self::TYPE_START => "После старта",
            self::TYPE_PAY => "После создания транзакции",
            self::TYPE_WASTE => "Перед созданием транзакции"
        ];
    }

    /**
     * @return string[]
     */
    public static function getConditions()
    {
        return [
            self::CONDITION_MORE => "Больше чем",
//            self::CONDITION_LESS => "Меньше чем"
        ];
    }

    /**
     * @return array|string[]
     */
    public static function getBots()
    {
        return [0 => "Все боты"] + ArrayHelper::map(Bot::find()->asArray()->all(), "id", "name");
    }

    /**
     * @param null $exclude
     * @return bool
     */
    public function processAll($exclude = null)
    {
        $this->_time = time();
        $this->_exclude = $exclude;

        switch ($this->type) {
            case self::TYPE_START: return $this->processTypeStart();
            case self::TYPE_PAY: return $this->processTypePay();
            case self::TYPE_WASTE: return $this->processTypeWaste();
        }
    }

    /**
     * @return bool
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function processTypeStart()
    {
        $users = $this->getUsers(self::TYPE_START);
        return $this->sendMessage($users);
    }

    /**
     * @return bool
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function processTypePay()
    {
        $users = $this->getUsers(self::TYPE_PAY);
        return $this->sendMessage($users);
    }

    /**
     * @return bool
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function processTypeWaste()
    {
        $users = $this->getUsers(self::TYPE_WASTE);
        return $this->sendMessage($users);
    }

    /**
     * @param User[] $users
     * @return bool
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function sendMessage($users)
    {
        if(!$users) return false;

        $n = 0;
        foreach ($users as $user) {
            $user->sendMessage($this->text, Keyboard::getMainKeyboard());
            $n++;
            if($n == 28) {
                $n = 0;
                sleep(1);
            }
        }
        return true;
    }

    /**
     * @param $type
     * @return User[]|array|\yii\db\ActiveRecord[]|null
     */
    private function getUsers($type)
    {
        $min = $this->_time - $this->condition_value - 20;
        $max = $min + 20;
        $conditionForBot = $this->_exclude ? ["not in", "bot_id", $this->_exclude] : [">", "bot_id", 0];

        switch ($type) {
            case self::TYPE_START: return User::find()
                ->where("(SELECT COUNT(*) FROM `transaction` `t` WHERE `t`.user_id = `user`.id) = 1")
                ->andWhere([">=", "`user`.`created_at`", $min])
                ->andWhere(["<=", "`user`.`created_at`", $max])
                ->andWhere($conditionForBot)
                ->all();
            case self::TYPE_PAY: return User::find()
                ->joinWith('transactions')
                ->where(["`transaction`.`status`" => Transaction::STATUS_NEW])
                ->andWhere([">=", "`transaction`.`created_at`", $min])
                ->andWhere(["<=", "`transaction`.`created_at`", $max])
                ->andWhere($conditionForBot)
                ->all();
            case self::TYPE_WASTE: return User::find()
                ->joinWith('userAction')
                ->where(["user_action.type" => UserAction::TYPE_CLICK_ON_DONATE])
                ->andWhere([">=", "`user_action`.`created_at`", $min])
                ->andWhere(["<=", "`user_action`.`created_at`", $max])
                ->andWhere($conditionForBot)
                ->all();
        }
        return null;
    }
}
