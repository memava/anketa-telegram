<?php

namespace app\models;

use app\helpers\CountryHelper;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "bot".
 *
 * @property int $id
 * @property int|null $platform
 * @property string|null $name
 * @property string|null $bot_name
 * @property string|null $token
 * @property string|null $reserve_bot
 * @property string|null $message_after_request_if_no_requests
 * @property int|null $free_requests
 * @property float|null $requests_for_ref
 * @property int|null $payment_system
 * @property int|null $request_counter
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $type
 * @property int|null $user_id
 *
 * @property-read int $needRefsForRequest
 * @property-read int $requestForOneRef
 * @property-read int $subscribersCount
 * @property-read int $subscribersDayCount
 * @property-read int $requestCount
 * @property-read int $requestDayCount
 * @property-read int $clicksDonateCount
 * @property-read int $clicksDonateDayCount
 * @property-read int $clicksPayCount
 * @property-read int $clicksPayDayCount
 * @property-read int $usersSumPaid
 *
 * @property-read string $webhookInfo
 * @property-read string $reserveLink
 *
 * @property-read BotCountries[] $countries
 * @property-read User $user
 */
class Bot extends \yii\db\ActiveRecord
{
	const PLATFORM_TELEGRAM = 1;
	const PLATFORM_VIBER = 2;

	const PAYMENT_QIWI = 1;
	const PAYMENT_EPAY = 2;
	const PAYMENT_QIWI_EPAY = 3;

	const TYPE_NORMAL = 1;
	const TYPE_WEBMASTER = 2;

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
        return 'bot';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['platform', 'free_requests', 'payment_system', 'created_at', 'updated_at', 'request_counter', 'type'], 'integer'],
            [['token', 'reserve_bot'], 'string'],
            [['requests_for_ref'], 'number'],
            [['name', 'bot_name', 'message_after_request_if_no_requests'], 'string', 'max' => 255],
			[['country_1', 'country_2', 'country_3', 'country_4'], 'boolean']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'platform' => 'Платформа',
            'name' => 'Название',
            'bot_name' => 'НазваниеМессен',
            'token' => 'АПИ ключ',
            'free_requests' => 'Бесплатные запросы',
            'requests_for_ref' => 'Кол-во реф. для 1 запроса',
            'payment_system' => 'Платежка',
            'created_at' => 'Создан',
            'updated_at' => 'Изменен',
			'message_after_request_if_no_requests' => "Сообщение после формирования запроса {link}"
        ];
    }

	/**
	 * @return string[]
	 */
	public static function getPlatforms()
	{
		return [
			self::PLATFORM_TELEGRAM => "Telegram"
		];
	}

	/**
	 * @return string[]
	 */
	public static function getTypes()
	{
		return [
			self::TYPE_NORMAL => "Обычный",
			self::TYPE_WEBMASTER => "Для вебмастеров",
		];
	}

	/**
	 * @return string[]
	 */
	public static function getPaymentSystems()
	{
		return [
			self::PAYMENT_QIWI => "Qiwi",
			self::PAYMENT_EPAY => "Epay",
			self::PAYMENT_QIWI_EPAY => "Qiwi + Epay",
		];
	}

	/**
	 * @param $botname
	 * @return Bot|null
	 */
	public static function findByBotname($botname)
	{
		return self::findOne(["bot_name" => $botname]);
	}

	/**
	 * @param $chat_id
	 * @param $botName
	 * @return \Longman\TelegramBot\Entities\ServerResponse
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public static function donate($chat_id, $botName)
	{
		$bot = self::findByBotname($botName)->id;
		$keyboard = Keyboard::getKeyboardFor(Keyboard::TYPE_DONATE, $bot);
		return Request::sendMessage(["chat_id" => $chat_id, "text" => "Донат", "reply_markup" => $keyboard]);
	}

	/**
	 * @param $chat_id
	 * @return \Longman\TelegramBot\Entities\ServerResponse
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public static function payment($chat_id, $data, $botUsername)
	{
		$user = User::findIdentityByAccessToken($chat_id, $botUsername);
		$ex = explode(' ', $data);
		if($user->country == CountryHelper::COUNTRY_UKRAINE) {
			$link = Transaction::changeBalance($user, $ex[2], $ex[1], $ex[0],Transaction::TYPE_PAYMENT, Transaction::CURRENCY_UAH);
		} else {
			$link = Transaction::changeBalance($user, $ex[2], $ex[0], $ex[0],Transaction::TYPE_PAYMENT, Transaction::CURRENCY_RUB);
		}

		if($user->bot->payment_system == Bot::PAYMENT_QIWI) {
			$btns[][0] = ["text" => "Visa/MasterCard/QIWI", "url" => $link["link_qiwi"]];
		} else if($user->bot->payment_system == Bot::PAYMENT_EPAY) {
			$btns[][0] = ["text" => "Visa/MasterCard", "url" => $link["link_epay"]];
		} else {
			$btns[][0] = ["text" => "Visa/MasterCard/QIWI", "url" => $link["link_qiwi"]];
			$btns[][0] = ["text" => "Способ 2: Visa/MasterCard", "url" => $link["link_epay"]];
		}
		$kbd = new InlineKeyboard(...$btns);
		return Request::sendMessage(["chat_id" => $chat_id, "text" => "Спасибо! Теперь оплатите!", "reply_markup" => $kbd]);
	}

	/**
	 * @return float
	 */
	public function getNeedRefsForRequest()
	{
		return round(1 / $this->requests_for_ref);
	}

	/**
	 * @return float|int|null
	 */
	public function getRequestForOneRef()
	{
		if($this->requests_for_ref > 1) {
			return $this->requests_for_ref;
		}
		return 1;
	}

	/**
	 * @return bool
	 */
	public function isRefSystemEnabled()
	{
		return (bool) $this->requests_for_ref;
	}

	/**
	 * @return bool
	 */
	public function getCountry_1()
	{
		return (bool) BotCountries::findOne(["country" => 1, "bot_id" => $this->id]);
	}

	/**
	 * @return bool
	 */
	public function getCountry_2()
	{
		return (bool) BotCountries::findOne(["country" => 2, "bot_id" => $this->id]);
	}

	/**
	 * @return bool
	 */
	public function getCountry_3()
	{
		return (bool) BotCountries::findOne(["country" => 3, "bot_id" => $this->id]);
	}

	/**
	 * @return bool
	 */
	public function getCountry_4()
	{
		return (bool) BotCountries::findOne(["country" => 4, "bot_id" => $this->id]);
	}

	/**
	 * @param $v
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function setCountry_1($v)
	{
		if($v) {
			$bc = new BotCountries();
			$bc->bot_id = $this->id;
			$bc->country = 1;
			$bc->save();
		} else {
			if($bc = BotCountries::findOne(['bot_id' => $this->id, 'country' => 1])) {
				$bc->delete();
			}
		}
	}

	/**
	 * @param $v
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function setCountry_2($v)
	{
		if($v) {
			$bc = new BotCountries();
			$bc->bot_id = $this->id;
			$bc->country = 2;
			$bc->save();
		} else {
			if($bc = BotCountries::findOne(['bot_id' => $this->id, 'country' => 2])) {
				$bc->delete();
			}
		}
	}

	/**
	 * @param $v
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function setCountry_3($v)
	{
		if($v) {
			$bc = new BotCountries();
			$bc->bot_id = $this->id;
			$bc->country = 3;
			$bc->save();
		} else {
			if($bc = BotCountries::findOne(['bot_id' => $this->id, 'country' => 3])) {
				$bc->delete();
			}
		}
	}

	/**
	 * @param $v
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function setCountry_4($v)
	{
		if($v) {
			$bc = new BotCountries();
			$bc->bot_id = $this->id;
			$bc->country = 4;
			$bc->save();
		} else {
			if($bc = BotCountries::findOne(['bot_id' => $this->id, 'country' => 4])) {
				$bc->delete();
			}
		}
	}

	/**
	 * @param $role
	 * @param $text
	 * @param null $reply
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public function sendFor($role, $text, $reply = null)
	{
		if(!$reply) {
			$reply = \Longman\TelegramBot\Entities\Keyboard::remove();
		}
		$users = User::findAll(["role" => $role, "bot_id" => $this->id]);
		if($users) {
			foreach ($users as $user) {
				$user->sendMessage($text, $reply);
			}
		}
	}

	/**
	 * @param $chat_id
	 * @return \Longman\TelegramBot\Entities\ServerResponse
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public static function mainMenu($chat_id)
	{
		$data = [
			"chat_id" => $chat_id,
			"text" => "Главное меню",
			"reply_markup" => Keyboard::getMainKeyboard()
		];
		return Request::sendMessage($data);
	}

    /**
     * @param $chat_id
     * @param $username
     * @param $name
     * @param $botUsername
     * @param $text
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws TelegramException
     */
	public static function newBot($chat_id, $username, $name, $botUsername, $text)
    {
        $model = CRequest::getOrSetRequest($chat_id, $botUsername);
        $model->sStatus(CRequest::STATUS_WEB_API_KEY);

        $text = Config::get(Config::VAR_TEXT_WEB_APIKEY);
        return $model->user->sendMessage($text, \Longman\TelegramBot\Entities\Keyboard::remove());
    }

    /**
     * @param $chat_id
     * @param $username
     * @param $name
     * @param $botUsername
     * @param $text
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws TelegramException
     */
	public static function acceptNewBot($id)
    {
        $model = CRequest::findOne($id);
        $model->sStatus(CRequest::STATUS_GENERATING);

        $bot = new Bot();
        $bot->token = $model->fio;
        $bot->bot_name = $model->city;
        $bot->name = $model->city;
        $bot->platform = Bot::PLATFORM_TELEGRAM;
        $bot->free_requests = $model->bot->free_requests;
        $bot->requests_for_ref = $model->bot->requests_for_ref;
        $bot->user_id = $model->user->token;
        $bot->payment_system = $model->bot->payment_system;
        $bot->type = self::TYPE_NORMAL;
        $bot->save(false);

        $text = Config::get(Config::VAR_TEXT_WEB_AFTER_CREATE);
        $model->user->sendMessage($text, \Longman\TelegramBot\Entities\Keyboard::remove());

        return $model->user->sendMessage("Ваши боты", Keyboard::getKeyboardFor(Keyboard::TYPE_BOTS, $bot->id));
    }

    /**
     * @param $id
     */
    public static function getBots($id)
    {
        $bot = Bot::findOne($id);
        return $bot->user->sendMessage("Ваши боты", Keyboard::getKeyboardFor(Keyboard::TYPE_BOTS, $id));
    }

	/**
	 * @param $chat_id
	 * @param $username
	 * @param $name
	 * @param $botUsername
	 * @param $text
	 * @return \Longman\TelegramBot\Entities\ServerResponse|mixed
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public static function startCommand($chat_id, $username, $name, $botUsername, $text)
	{
		if(!User::findIdentityByAccessToken($chat_id, $botUsername)) {
			self::registerUser($chat_id, $username, $name, $botUsername, $text);
			return self::sendCountryMessage($chat_id, $botUsername);
		} else if(!self::checkIsCountryFilled($chat_id, $botUsername)) {
			return self::sendCountryMessage($chat_id, $botUsername);
		}

		return CRequest::newRequest($chat_id, $botUsername);
	}

	/**
	 * @return bool
	 */
	public static function registerUser($chat_id, $username, $name, $botUsername, $text, $role = 1)
	{
	    $bot = Bot::findByBotname($botUsername);
		$user = new User();
		$user->token = $chat_id;
		$user->username = $username;
		$user->name = $name;
		$user->bot_id = $bot->id;
		$user->role = $role;
		if($text) {
			if($u = User::findByRefLink($text)) {
				$user->ref_id = $u->id;
			}
		}
		if($bot->user_id == $chat_id) {
		    $user->role = User::ROLE_ADMIN;
        }
		return $user->save(false);
	}

	/**
	 * @return mixed
	 * @throws TelegramException
	 */
	private static function sendCountryMessage($chat_id, $botUsername)
	{
		$keyboard = self::getCountryKeyboardFor(User::findIdentityByAccessToken($chat_id, $botUsername)->bot_id);

		return Request::sendMessage(["text" => "Выберите страну", "chat_id" => $chat_id, "reply_markup" => $keyboard]);
	}

	/**
	 * @param $bot_id
	 * @return \Longman\TelegramBot\Entities\InlineKeyboard
	 */
	public static function getCountryKeyboardFor($bot_id)
	{
		$countries = BotCountries::findAll(["bot_id" => $bot_id]);
		foreach ($countries as $country) {
			$k[] = $country->country;
		}
		return CountryHelper::getKeyboardFor($k);
	}

	/**
	 * @return bool
	 */
	public static function checkIsCountryFilled($chat_id, $botUsername)
	{
		return (bool) User::findIdentityByAccessToken($chat_id, $botUsername)->country;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCountries()
	{
		return $this->hasMany(BotCountries::class, ["bot_id" => $this->id]);
	}

	/**
	 * @param bool $runValidation
	 * @param null $attributeNames
	 * @return bool
	 */
	public function save($runValidation = true, $attributeNames = null)
	{
		$isNewRecord = $this->isNewRecord;
		if(parent::save($runValidation, $attributeNames)) {
			if($isNewRecord) {
				return $this->webhook();
			}
			$this->setCountry_1($this->country_1);
			$this->setCountry_2($this->country_2);
			$this->setCountry_3($this->country_3);
			$this->setCountry_4($this->country_4);
		}
		return false;
	}

	public function dropHook()
	{
		$bot_api_key  = $this->token;
		$bot_username = $this->bot_name;
		$hook_url     = Url::to(["api/webhook", "id" => $this->id], true);

		try {
			// Create Telegram API object
			$telegram = new Telegram($bot_api_key, $bot_username);

			// Set webhook
			$result = $telegram->deleteWebhook();
			if ($result->isOk()) {
				return true;
			}
		} catch (TelegramException $e) {
			return false;
			// log telegram errors
			// echo $e->getMessage();
		}
		return false;
	}

	/**
	 * @return bool
	 */
	public function webhook($a = false)
	{
		$bot_api_key  = $this->token;
		$bot_username = $this->bot_name;
		if(!$a) {
		    if($this->type == Bot::TYPE_NORMAL) {
                $hook_url = Url::to(["api/webhook", "id" => $this->id], "https");
            } else {
		        $hook_url = Url::to(["api/webmaster", "id" => $this->id], "https");
            }
		} else {
			$hook_url = Url::to(["api/webhoook"], "https");
		}

		try {
			// Create Telegram API object
			$telegram = new Telegram($bot_api_key, $bot_username);

			// Set webhook
			$result = $telegram->setWebhook($hook_url);
			if ($result->isOk()) {
				return true;
			}
		} catch (TelegramException $e) {
			return false;
			// log telegram errors
			// echo $e->getMessage();
		}
		return false;
	}

	/**
	 * @return bool|int|string|null
	 */
	public function getSubscribersCount()
	{
		return User::find()->where(["bot_id" => $this->id])->count();
	}

	/**
	 * @return bool|int|string|null
	 */
	public function getSubscribersDayCount()
	{
		return User::find()->where(["bot_id" => $this->id])->andWhere([">", "created_at", strtotime("today", time())])->count();
	}

	/**
	 * @return bool|int|string|null
	 */
	public function getRequestCount()
	{
		return $this->request_counter;
	}

	/**
	 * @return bool|int|string|null
	 */
	public function getRequestDayCount()
	{
		$startDay = strtotime("today", time());
		$a = Transaction::find()
			->joinWith('user')
			->where(['user.bot_id' => $this->id])
			->andWhere([">", "transaction.created_at", $startDay])
			->sum(new Expression("if(balance_after < balance_before, 1, 0)"));
		return $a;
	}

	/**
	 * @return bool|int|string|null
	 */
	public function getClicksDonateCount()
	{
		return Transaction::find()->joinWith('user')->where(["user.bot_id" => $this->id])->andWhere(["type" => Transaction::TYPE_PAYMENT])->count();
	}

	/**
	 * @return bool|int|string|null
	 */
	public function getClicksDonateDayCount()
	{
		return Transaction::find()
			->joinWith('user')
			->where(["user.bot_id" => $this->id])
			->andWhere(["type" => Transaction::TYPE_PAYMENT])
			->andWhere([">", "transaction.created_at", strtotime("today", time())])
			->count();
	}

	/**
	 * @return bool|int|string|null
	 */
	public function getClicksPayCount()
	{
		return Transaction::find()
			->joinWith('user')
			->where(["user.bot_id" => $this->id])
			->andWhere(["type" => Transaction::TYPE_PAYMENT, "status" => [Transaction::STATUS_END, Transaction::STATUS_NEW]])
			->count();
	}

	/**
	 * @return bool|int|string|null
	 */
	public function getClicksPayDayCount()
	{
		return Transaction::find()
			->joinWith('user')
			->where(["user.bot_id" => $this->id])
			->andWhere(["type" => Transaction::TYPE_PAYMENT, "status" => [Transaction::STATUS_END, Transaction::STATUS_NEW]])
			->andWhere([">", "transaction.created_at", strtotime("today", time())])
			->count();
	}

	/**
	 * @return bool|int|mixed|string
	 */
	public function getUsersSumPaid()
	{
		return Transaction::find()
			->joinWith('user')
			->where([
				"type" => Transaction::TYPE_PAYMENT,
				"status" => Transaction::STATUS_END,
				"user.bot_id" => $this->id
			])
			->sum(new Expression("if(balance_before < balance_after, if(currency = ".Transaction::CURRENCY_UAH.", sum_uah, sum_rub), 0)")) ?: 0;
	}

	/**
	 * @param int $limit
	 * @return Transaction[]|array|\yii\db\ActiveRecord[]|null
	 */
	public function checks($limit = 1)
	{
		$transaction = Transaction::find()
			->joinWith('user')
			->where(["user.bot_id" => $this->id, "type" => Transaction::TYPE_PAYMENT, "status" => Transaction::STATUS_END])
			->orderBy(['id' => SORT_DESC])
			->limit($limit)
			->all();
		return $transaction ?: null;
	}

	/**
	 * @return string
	 * @throws TelegramException
	 */
	public function getWebhookInfo()
	{
		$tg = new Telegram($this->token, $this->bot_name);
		$info = Request::getWebhookInfo();
		return VarDumper::dumpAsString($info->getResult());
	}

	/**
	 * @return string|null
	 */
	public function getReserveLink()
	{
		return $this->reserve_bot ? : Config::get(Config::VAR_DEFAULT_RESERVE_BOT);
	}

	/**
	 * @return array
	 */
	public function topRefs($cnt, $forDay = true)
	{
		$users = User::find()->where(["bot_id" => $this->id]);
		if($forDay) {
			$startDay = strtotime("today", time());
			$users = $users->andWhere([">", "created_at", $startDay]);
		}
		$users = $users->all();
		$array = [];
		foreach ($users as $user) {
			$firstRef = $user->getFirstRef();
			if(!isset($array[$firstRef->id])) {
				$array[$firstRef->id] = ["model" => $firstRef, "count" => -1];
			}
			$array[$firstRef->id]["count"]++;
		}
		$array = array_filter($array, function ($v) {
			return $v["count"];
		});
		usort($array, function ($a, $b){
			return $a["count"] < $b["count"];
		});
		return array_slice($array, 0, $cnt);
	}

    /**
     * @return \yii\db\ActiveQuery
     */
	public function getUser()
    {
        return $this->hasOne(User::class, ["token" => $this->user_id]);
    }
}
