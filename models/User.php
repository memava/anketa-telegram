<?php

namespace app\models;

use app\events\BalanceChangedEvent;
use app\events\UserRegisteredEvent;
use app\helpers\CountryHelper;
use app\helpers\KeyboardHelper;
use Longman\TelegramBot\Entities\Factory;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\console\Application;
use yii\db\Expression;
use yii\helpers\Console;
use yii\helpers\VarDumper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property int|null $bot_id
 * @property string|null $token
 * @property string|null $username
 * @property string|null $name
 * @property int|null $gender
 * @property int|null $country
 * @property int|null $ref_id
 * @property string|null $ref_link
 * @property int|null $role
 * @property int|null $available_requests
 * @property int|null $ref_counter
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property-read int $requestCount
 * @property-read int $refCount
 * @property-read int $paidRequestCount
 * @property-read int $addedRequestCount
 * @property-read int $paidSumCount
 *
 * @property-read Bot $bot
 * @property-read User $ref
 * @property-read User $firstRef
 * @property-read string $refLink
 * @property-read null|string $authKey
 * @property-read UserAction[] $userAction
 * @property-read Transaction[] $transactions
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
	const GENDER_MALE = 0;
	const GENDER_FEMALE = 1;

	const ROLE_USER = 1;
	const ROLE_ADMIN = 2;
	const ROLE_WEB = 3;

	const EVENT_USER_REGISTERED = "user_registered";
	const EVENT_BALANCE_CHANGED = "balance_changed";

	/**
	 *
	 */
	public function init()
	{
		parent::init();
		$this->on(self::EVENT_USER_REGISTERED, [$this, 'userRegistered']);
		$this->on(self::EVENT_BALANCE_CHANGED, [$this, 'balanceChanged']);
	}

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
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bot_id', 'gender', 'country', 'ref_id', 'role', 'available_requests', 'ref_counter', 'created_at', 'updated_at'], 'integer'],
            [['token'], 'string'],
            [['username', 'ref_link'], 'string', 'max' => 255],
			["name", "safe"]
        ];
    }

	public static function getRoles()
	{
		return [
			self::ROLE_USER => "????????????????????????",
			self::ROLE_ADMIN => "??????????????????????????"
		];
	}

	/**
	 * @param int $lang
	 * @return string[]|void
	 */
	public static function getGenders($lang = CRequest::LANGUAGE_RU)
	{
		switch ($lang) {
			case CRequest::LANGUAGE_RU:
				return [
					self::GENDER_MALE => "??????????????",
					self::GENDER_FEMALE => "??????????????",
				];
			case CRequest::LANGUAGE_UA:
				return [
					self::GENDER_MALE => "??????????i????",
					self::GENDER_FEMALE => "??i????????",
				];
			case CRequest::LANGUAGE_EN:
				return [
					self::GENDER_MALE => "Male",
					self::GENDER_FEMALE => "Female",
				];
		}

	}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bot_id' => 'Bot ID',
            'token' => '??????????????????????????',
            'username' => '??????????',
            'name' => '??????',
            'gender' => '??????',
            'country' => '????????????',
            'ref_id' => '??????????????',
            'ref_link' => '??????. ????????????',
            'role' => '????????',
            'available_requests' => '???????????????? ????????????????',
            'created_at' => '??????????????????????????',
            'updated_at' => 'Updated At',
        ];
    }

	/**
	 * Finds an identity by the given ID.
	 * @param string|int $id the ID to be looked for
	 * @return IdentityInterface|null the identity object that matches the given ID.
	 * Null should be returned if such an identity cannot be found
	 * or the identity is not in an active state (disabled, deleted, etc.)
	 */
	public static function findIdentity($id)
	{
		return self::findOne($id);
	}

	/**
	 * Finds an identity by the given token.
	 * @param mixed $token the token to be looked for
	 * @param string $bot the type of the token. The value of this parameter depends on the implementation.
	 * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
	 * @return User|null the identity object that matches the given token.
	 * Null should be returned if such an identity cannot be found
	 * or the identity is not in an active state (disabled, deleted, etc.)
	 */
	public static function findIdentityByAccessToken($token, $bot = null)
	{
		if($bot) {
			$bot = Bot::findByBotname($bot);
			if($bot) return self::findOne(["token" => $token, "bot_id" => $bot->id]);
			return null;
		}
		return self::findOne(["token" => $token]);
	}

	/**
	 * @param $link
	 * @return User|null
	 */
	public static function findByRefLink($link)
	{
		return self::findOne(["ref_link" => $link]);
	}

	/**
	 * Returns an ID that can uniquely identify a user identity.
	 * @return string|int an ID that uniquely identifies a user identity.
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Returns a key that can be used to check the validity of a given identity ID.
	 *
	 * The key should be unique for each individual user, and should be persistent
	 * so that it can be used to check the validity of the user identity.
	 *
	 * The space of such keys should be big enough to defeat potential identity attacks.
	 *
	 * The returned key is used to validate session and auto-login (if [[User::enableAutoLogin]] is enabled).
	 *
	 * Make sure to invalidate earlier issued authKeys when you implement force user logout, password change and
	 * other scenarios, that require forceful access revocation for old sessions.
	 *
	 * @return string|null a key that is used to check the validity of a given identity ID.
	 * @see validateAuthKey()
	 */
	public function getAuthKey()
	{
		return $this->token.$this->created_at;
	}

	/**
	 * Validates the given auth key.
	 *
	 * @param string $authKey the given auth key
	 * @return bool|null whether the given auth key is valid.
	 * @see getAuthKey()
	 */
	public function validateAuthKey($authKey)
	{
		return $this->token.$this->created_at == $authKey;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRef()
	{
		return $this->hasOne(self::class, ["id" => "ref_id"]);
	}

	/**
	 * @throws \yii\base\Exception
	 */
	public function generateRefLink()
	{
		$this->ref_link = preg_replace('/[^a-zA-Z0-9]/', '', Yii::$app->security->generateRandomString(16));
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBot()
	{
		return $this->hasOne(Bot::class, ["id" => "bot_id"]);
	}

	/**
	 * @param bool $insert
	 * @return bool
	 * @throws \yii\base\Exception
	 */
	public function beforeSave($insert)
	{
		if($this->isNewRecord) {
			$this->generateRefLink();
			$this->role = $this->role ?: self::ROLE_USER;
		}
		return parent::beforeSave($insert);
	}

	/**
	 * @param bool $runValidation
	 * @param null $attributeNames
	 * @return bool
	 */
	public function save($runValidation = true, $attributeNames = null)
	{
		$isNewRecord = $this->isNewRecord;
		$s = parent::save($runValidation, $attributeNames);
		if($s && $isNewRecord) {
			$event = new UserRegisteredEvent();
			$event->user = $this;
			$this->trigger(self::EVENT_USER_REGISTERED, $event);
		}
		return $s;
	}

	/**
	 * @param bool $insert
	 * @param array $changedAttributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);
	}

	/**
	 * @param $chat_id
	 * @param $country
	 * @return \Longman\TelegramBot\Entities\ServerResponse
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public static function selectCountry($chat_id, $country, $botUsername)
	{
		if(User::findIdentityByAccessToken($chat_id, $botUsername) && in_array($country, CountryHelper::COUNTRIES)) {
			$user = User::findIdentityByAccessToken($chat_id, $botUsername);
			$user->country = $country;

			$user->sendMessage("???????????? ?????????????? ????????????????!", Keyboard::getMainKeyboard());

			if($user->available_requests == 0 && !CRequest::isRequests($user->id)) $user->available_requests = $user->bot->free_requests;
			$user->save(false);
			return CRequest::newRequest($chat_id, $botUsername);
		} else {
			return Request::sendMessage([
				"text" => "???????????????? ????????????!",
				"chat_id" => $chat_id,
				"reply_markup" => CountryHelper::getKeyboard()
			]);
		}
	}

	/**
	 * @return int|null
	 */
	public function getAvailable_requests()
	{
		$t = Transaction::find()->where(["user_id" => $this->id, "status" => Transaction::STATUS_END])->orderBy(["id" => SORT_DESC])->one();
		return $t ? $t->balance_after : 0;
	}

	/**
	 * @param $value
	 * @return bool
	 */
	public function setAvailable_requests($value)
	{
		return Transaction::changeBalance($this, $value);
	}

	/**
	 * @param $chat_id
	 * @return \Longman\TelegramBot\Entities\ServerResponse
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public static function myProfile($chat_id, $botUsername)
	{
		$user = self::findIdentityByAccessToken($chat_id, $botUsername);
		$text = "*?????? ????????????:* ".CountryHelper::getCountries()[$user->country]."\n".
			"*???????????????????? ????????????:* $user->refCount\n".
			"*???????????????? ????????????????:* ".$user->available_requests."\n".
			"*???????????? ?????? ?????????????????????? ????????????:* ".$user->getRefLink();
		$kbd = new \Longman\TelegramBot\Entities\Keyboard([KeyboardHelper::BTN_MAIN_MENU], [KeyboardHelper::BTN_CHANGE_COUNTRY]);
		$text = str_replace(explode(" ", "_ { } + !"), ["\_", "\{", "\}", "\+", "\!"], $text);
		$kbd->setResizeKeyboard(true);
		return $user->sendMessage($text, $kbd);
	}

	public function getRefLink()
	{
		return "t.me/".$this->bot->bot_name."?start=".$this->ref_link;
	}

	/**
	 * @param $num
	 * @return bool
	 */
	public function changeBalance($num)
	{
		$this->available_requests += $num;
		return $this->save(false);
	}

	/**
	 * @return bool
	 */
	public function increaseRef()
	{
		$this->ref_counter += 1;
		return $this->save(false);
	}

	/**
	 * @return bool
	 */
	public function resetRef()
	{
		$this->ref_counter = 0;
		return $this->save(false);
	}

	/**
	 * @param UserRegisteredEvent $event
	 */
	public function userRegistered(UserRegisteredEvent $event)
	{
		if($event->bot->isRefSystemEnabled() && $event->isRef()) {
			$event->ref->increaseRef();
			if($event->ref->ref_counter >= $event->bot->needRefsForRequest) {
				$event->ref->changeBalance($event->bot->requestForOneRef);
				$event->ref->resetRef();
			}

			$text = "\xF0\x9F\x91\xA4 ???????????????????????? [{$event->ref->token}](tg://user?id={$event->ref->token}) @{$event->ref->username} ({$event->ref->name}) ???????????? [{$event->user->token}](tg://user?id={$event->user->token}) @{$event->user->username}";
			$event->bot->sendFor(User::ROLE_ADMIN, $text);
		}
	}

	/**
	 * @param BalanceChangedEvent $event
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public function balanceChanged(BalanceChangedEvent $event)
	{
		if($event->isIncrease()) {
			$text = "*?????????????????? ????????????????: $event->count.* ?????????????????? ??????????????????????.";
			$event->user->sendMessage($text, Keyboard::getMainKeyboard());

			if($event->isExternalPayment()) {
				$event->user->bot->sendFor(User::ROLE_ADMIN, "\xE2\x9C\x85 \xE2\x9C\x85 \xE2\x9C\x85 *???????????????? {$event->sum} ????????????* ???? {$event->count} ???????????????? ???? @{$event->user->username} : {$event->user->name} : [{$event->user->token}](tg://user?id={$event->user->token})");
			}
		}
	}

	/**
	 * @param $text
	 * @param $reply
	 * @return \Longman\TelegramBot\Entities\ServerResponse
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public function sendMessage($text, $reply)
	{
        if($this->bot && $this->bot->token && $this->bot->name) {
            $tg = new Telegram($this->bot->token, $this->bot->bot_name);
            $r = Request::sendMessage(["chat_id" => $this->token, "text" => $text, "reply_markup" => $reply, "parse_mode" => "markdown"]);
            if (Yii::$app instanceof Application) {
                Console::output("Send message for $this->token ($text)");
            }
            //file_put_contents(Yii::getAlias("@app/log"), VarDumper::dumpAsString($r).PHP_EOL, FILE_APPEND);
            return $r;
        }
	}

	/**
	 * @return bool
	 */
	public function canCreateNewRequest()
	{
		return $this->available_requests > 0;
	}

	/**
	 * @return int
	 */
	public function getRequestCount()
	{
		$a = Transaction::find()
			->where(['user_id' => $this->id])
			->sum(new Expression("if(balance_after < balance_before, 1, 0)"));
		return $a;
	}

    /**
     * @return int
     */
    public function getUserCountry(){

    }

	/**
	 * @return int
	 */
	public function getRefCount()
	{
		return User::find()->where(["ref_id" => $this->id])->count();
	}

	/**
	 * @return bool|int|mixed|string|null
	 */
	public function getPaidRequestCount()
	{
		return Transaction::find()->where(["type" => Transaction::TYPE_PAYMENT, "user_id" => $this->id, "status" => Transaction::STATUS_END])->sum(new Expression("if(balance_before < balance_after, (balance_after - balance_before), 0)")) ?: 0;
	}

	/**
	 * @return bool|int|mixed|string|null
	 */
	public function getPaidSumCount()
	{
		return Transaction::find()->where(["type" => Transaction::TYPE_PAYMENT, "user_id" => $this->id, "status" => Transaction::STATUS_END])->sum(new Expression("if(balance_before < balance_after, if(currency = ".Transaction::CURRENCY_UAH.", sum_uah, sum_rub), 0)")) ?: 0;
	}

	/**
	 * @return bool|int|mixed|string
	 */
	public function getAddedRequestCount()
	{
		return Transaction::find()
			->where(["type" => Transaction::TYPE_MANUAL, "user_id" => $this->id, "status" => Transaction::STATUS_END])
			->sum(new Expression("if(balance_before < balance_after, (balance_after - balance_before), 0)")) ?: 0;
	}

	/**
	 * @return bool
	 */
	public function isAdmin()
	{
		return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_WEB]);
	}

    /**
     * @return $this
     */
	public function getFirstRef()
	{
		if($r = $this->ref) {
			return $r->getFirstRef();
		} else {
			return $this;
		}
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::class, ["user_id" => "id"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAction()
    {
        return $this->hasMany(UserAction::class, ["user_id" => "id"]);
    }

    /**
     * @param $buttons
     * @return mixed
     */
    public function textDonate($buttons)
    {
        /** @var InlineKeyboard $buttons */
        foreach ($buttons->getProperty('inline_keyboard') as &$button) {
            /** @var InlineKeyboardButton $kkButton */
            foreach ($button as &$kkButton) {
                if($ex = explode(" ", $kkButton->getCallbackData())) {
                    if($ex[0] != "/donate") continue;
                } else {
                    continue;
                }
                $uah = $ex[2];
                $rub = $ex[1];
                if($this->country == CountryHelper::COUNTRY_UKRAINE) {
                    $cash = $uah;
                } else {
                    $cash = $rub;
                }
                $kkButton->setText(str_replace("{price}", $cash . CountryHelper::getCountryCurrency($this->country), $kkButton->getText()));
            }
        }
        return $buttons;
    }

}
