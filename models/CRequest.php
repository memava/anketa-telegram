<?php

namespace app\models;

use app\events\CRequestMadeEvent;
use app\helpers\CountryHelper;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "crequest".
 *
 * @property int $id
 * @property int|null $bot_id
 * @property int|null $user_id
 * @property string|null $unique_id
 * @property int|null $city
 * @property int|null $language
 * @property int|null $fio
 * @property int|null $gender
 * @property string|null $birthday
 * @property string|null $request_date
 * @property string|null $slug
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property-read Bot $bot
 * @property-read User $user
 */
class CRequest extends \yii\db\ActiveRecord
{
	const EVENT_MADE = "onMade";

	const LANGUAGE_RU = 1;
	const LANGUAGE_EN = 2;
	const LANGUAGE_UA = 3;

	const LANGUAGES = [
		self::LANGUAGE_RU => "Русский",
		self::LANGUAGE_UA => "Украинский",
		self::LANGUAGE_EN => "Английский"
	];

	//const STATUS_NEW = 0;
	const STATUS_SELECT_LANGUAGE = 1;
	const STATUS_SELECT_FIO = 2;
	const STATUS_SELECT_GENDER = 3;
	const STATUS_SELECT_BIRTHDAY = 4;
	const STATUS_SELECT_DATE = 5;
	const STATUS_SELECT_CITY = 6;
	const STATUS_CHECKING = 7;
	const STATUS_GENERATING = 8;
	const STATUS_ACTIVE = 10;
	const STATUS_INACTIVE = 0;

	const STATUS_WEB_API_KEY = 2;
	const STATUS_WEB_NAME = 4;

	const STATUSES_CREATING = [
		self::STATUS_SELECT_LANGUAGE,
		self::STATUS_SELECT_FIO,
		self::STATUS_SELECT_GENDER,
		self::STATUS_SELECT_BIRTHDAY,
		self::STATUS_SELECT_DATE,
		self::STATUS_SELECT_CITY,
		self::STATUS_CHECKING,
		self::STATUS_GENERATING,
	];

	const STATUSES_INPUT = [
		self::STATUS_SELECT_FIO,
		self::STATUS_SELECT_BIRTHDAY,
		self::STATUS_SELECT_DATE,
		self::STATUS_SELECT_CITY,
	];

	const EXPIRE = 72 * 60 * 60;

	const DATE = 30 * 60;

	/**
	 * @return string[]
	 */
	public static function getStatuses()
	{
		return [
			self::STATUS_SELECT_LANGUAGE => "Выбор языка",
			self::STATUS_SELECT_FIO => "Ввод ФИО",
			self::STATUS_SELECT_GENDER => "Выбор пола",
			self::STATUS_SELECT_BIRTHDAY => "Ввод даты рождения",
			self::STATUS_SELECT_DATE => "Ввод даты тестирования",
			self::STATUS_SELECT_CITY => "Ввод города",
			self::STATUS_CHECKING => "Проверка",
			self::STATUS_GENERATING => "Формирование",
			self::STATUS_ACTIVE => "Сформирован",
			self::STATUS_INACTIVE => "Неактивен"
		];
	}

	/**
	 *
	 */
	public function init()
	{
		parent::init();
		$this->on(self::EVENT_MADE, [$this, "made"]);
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
        return 'crequest';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bot_id', 'user_id', 'city', 'language', 'fio', 'gender', 'status', 'created_at', 'updated_at'], 'integer'],
            [['unique_id', 'birthday', 'request_date', 'slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bot_id' => 'Бот',
            'user_id' => 'Пользователь',
            'unique_id' => 'Уникальный код',
            'city' => 'Город',
            'language' => 'Язык',
            'fio' => 'ФИО',
            'gender' => 'Пол',
            'birthday' => 'Дата рождения',
            'request_date' => 'Дата запроса',
            'slug' => 'Slug',
            'status' => 'Статус',
            'created_at' => 'Создан',
            'updated_at' => 'Изменен',
        ];
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(User::class, ["id" => "user_id"]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBot()
	{
		return $this->hasOne(Bot::class, ["id" => 'bot_id']);
	}

	/**
	 * @throws \yii\base\Exception
	 */
	public function generateUniqueId()
	{
		$this->unique_id = rand(10000000, 99999999);
	}

	/**
	 * @throws \yii\base\Exception
	 */
	public function generateSlug()
	{
		$this->slug = rand(100000000, 999999999);
	}

	/**
	 * @param $chat_id
	 * @return \Longman\TelegramBot\Entities\ServerResponse
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public static function newRequest($chat_id, $botUsername)
	{
		$user = User::findIdentityByAccessToken($chat_id, $botUsername);
		if(!$user->canCreateNewRequest()) {

			$text = Config::get(Config::VAR_TEXT_NO_REQUESTS);
			$kbd = Keyboard::getKeyboardFor(Keyboard::TYPE_DONATE, $user->bot->id);
			$user->sendMessage($text, \Longman\TelegramBot\Entities\Keyboard::remove());

			$message = str_replace("{link}", $user->getRefLink(), $user->bot->message_after_request_if_no_requests);
			$message = str_replace(explode(" ", "* _ { } +"), ["\*", "\_", "\{", "\}", "\+"], $message);

			return $user->sendMessage($message, $kbd);
		}

		$model = self::getOrSetRequest($chat_id, $botUsername);
		$text = Config::get(Config::VAR_TEXT_STEP_ONE);
		$kbd = [];
		foreach (CountryHelper::getCountryLanguages($user->country) as $k => $lang) {
			$kbd[][0] = ["text" => self::LANGUAGES[$lang], "callback_data" => "/selectlanguage ".$lang];
		}
		$keyboard = new InlineKeyboard(...$kbd);
		$model->sStatus(self::STATUS_SELECT_LANGUAGE);

		return $model->user->sendMessage($text, $keyboard);
	}

	/**
	 * @param $chat_id
	 * @param $lang
	 * @return \Longman\TelegramBot\Entities\ServerResponse
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public static function selectLanguage($chat_id, $lang, $botUsername)
	{
		$model = self::getOrSetRequest($chat_id, $botUsername);
		$model->language = $lang;
		$model->sStatus(self::STATUS_SELECT_FIO);

		$text = Config::get(Config::VAR_TEXT_STEP_TWO).self::LANGUAGES[$lang];
		return $model->user->sendMessage($text,\Longman\TelegramBot\Entities\Keyboard::remove());
	}

	/**
	 * @param $chat_id
	 * @param $fio
	 * @return \Longman\TelegramBot\Entities\ServerResponse
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public static function selectFio($chat_id, $fio, $botUsername)
	{
		$model = self::getOrSetRequest($chat_id, $botUsername);
		$model->fio = $fio;
		$model->sStatus(self::STATUS_SELECT_GENDER);

		$text = Config::get(Config::VAR_TEXT_STEP_THREE);
		$keyboard = new InlineKeyboard([["text" => "Мужской", "callback_data" => "/selectgender ".User::GENDER_MALE]], [["text" => "Женский", "callback_data" => "/selectgender ".User::GENDER_FEMALE]]);
		return $model->user->sendMessage($text, $keyboard);
	}

	/**
	 * @param $chat_id
	 * @param $gender
	 * @return \Longman\TelegramBot\Entities\ServerResponse
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public static function selectGender($chat_id, $gender, $botUsername)
	{
		$model = self::getOrSetRequest($chat_id, $botUsername);
		$model->gender = $gender;
		$model->sStatus(self::STATUS_SELECT_BIRTHDAY);

		$text = Config::get(Config::VAR_TEXT_STEP_FOUR);
		return $model->user->sendMessage($text, \Longman\TelegramBot\Entities\Keyboard::remove());
	}

	/**
	 * @param $chat_id
	 * @param $date
	 * @return \Longman\TelegramBot\Entities\ServerResponse
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public static function selectBirthday($chat_id, $date, $botUsername)
	{
		$model = self::getOrSetRequest($chat_id, $botUsername);
//		if(!preg_grep('/^\s*(3[01]|[12][0-9]|0?[1-9])\.(1[012]|0?[1-9])\.((?:19|20)\d{2})\s*$/', explode("\n", $date))) {
//			return self::selectGender($chat_id, $model->gender, $botUsername);
//		}
		$model->birthday = $date;
		$model->sStatus(self::STATUS_SELECT_DATE);

		$text = Config::get(Config::VAR_TEXT_STEP_FIVE);
		$kbd = new \Longman\TelegramBot\Entities\Keyboard([["text" => date("d.m.Y", strtotime(date("Y-m-d"). " -1 day"))]], [["text" => date("d.m.Y", strtotime(date("Y-m-d")))]], [["text" => date("d.m.Y", strtotime(date("Y-m-d"). " +1 day"))]]);
		$kbd->setResizeKeyboard(true)->setOneTimeKeyboard(true);
		return $model->user->sendMessage($text, $kbd);
	}

	/**
	 * @param $chat_id
	 * @param $date
	 * @return \Longman\TelegramBot\Entities\ServerResponse
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public static function selectDate($chat_id, $date, $botUsername)
	{
		$model = self::getOrSetRequest($chat_id, $botUsername);
//		if(!preg_grep('/^\s*(3[01]|[12][0-9]|0?[1-9])\.(1[012]|0?[1-9])\.((?:19|20)\d{2})\s*$/', explode("\n", $date))) {
//			return self::selectBirthday($chat_id, $model->birthday, $botUsername);
//		}
		$model->request_date = $date;
		$model->sStatus(self::STATUS_SELECT_CITY);

		$text = Config::get(Config::VAR_TEXT_STEP_SIX);
		$kbd = \Longman\TelegramBot\Entities\Keyboard::remove();
		if($model->user->country == CountryHelper::COUNTRY_UKRAINE) {
			$kbd = Keyboard::getCityKeyboard(CountryHelper::COUNTRY_UKRAINE);
			$kbd = new \Longman\TelegramBot\Entities\Keyboard(...$kbd);
			$kbd->setResizeKeyboard(true)->setOneTimeKeyboard(true);
		}
		return $model->user->sendMessage($text, $kbd);
	}

	/**
	 * @param $chat_id
	 * @param $city
	 * @return \Longman\TelegramBot\Entities\ServerResponse
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public static function selectCity($chat_id, $city, $botUsername)
	{
		$model = self::getOrSetRequest($chat_id, $botUsername);
		$model->city = $city;
		$model->sStatus(self::STATUS_CHECKING);

		$text = "Проверьте правильность введеных данны!\n".
			"ФИО: ".$model->fio."\n".
			"Дата рождения: ".$model->birthday."\n".
			"Дата проведения запроса: ".$model->request_date."\n".
			"Город проведения запроса: ".$model->city."\n";
		$kbd = new InlineKeyboard([["text" => "Все верно", "callback_data" => "/generate ".$model->id]], [["text" => "Ввести заново", "callback_data" => "/newrequest"]]);
		return $model->user->sendMessage($text, $kbd);
	}

    /**
     * @param $chat_id
     * @param $apiKey
     * @param $botUsername
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
	public static function selectApiKey($chat_id, $apiKey, $botUsername)
    {
        $model = self::getOrSetRequest($chat_id, $botUsername);
        $model->fio = $apiKey;
        $model->sStatus(self::STATUS_WEB_NAME);

        $text = Config::get(Config::VAR_TEXT_WEB_NAME);
        return $model->user->sendMessage($text, \Longman\TelegramBot\Entities\Keyboard::remove());
    }

    /**
     * @param $chat_id
     * @param $apiKey
     * @param $botUsername
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
	public static function selectName($chat_id, $name, $botUsername)
    {
        $model = self::getOrSetRequest($chat_id, $botUsername);
        $model->city = $name;
        $model->sStatus(self::STATUS_WEB_NAME);

        $text = "Проверьте данные!\nToken: $model->fio\nName: $model->city";
        $kbd = new InlineKeyboard([["text" => "Правильно", "callback_data" => "/accept ".$model->id]]);
        return $model->user->sendMessage($text, $kbd);
    }

	/**
	 * @param $chat_id
	 * @param $id
	 * @return \Longman\TelegramBot\Entities\ServerResponse|void
	 * @throws \ImagickException
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public static function generate($chat_id, $id)
	{
		$model = self::findOne($id);

		if(!$model->canGenerate()) return Request::emptyResponse();

		$model->sStatus(self::STATUS_GENERATING);

		$text = Config::get(Config::VAR_TEXT_BEFORE_MAKE);
		$model->user->sendMessage($text, \Longman\TelegramBot\Entities\Keyboard::remove());

		if($model->make()) {
			$model->sStatus(self::STATUS_ACTIVE);
			return Request::emptyResponse();
		}
	}

	public function canGenerate()
	{
		return $this->status == self::STATUS_CHECKING;
	}

	/**
	 * @param $status
	 */
	public function sStatus($status)
	{
		$this->status = $status;
		$this->save(false);
	}

	/**
	 * @param $chat_id
	 * @return CRequest|null
	 */
	public static function getOrSetRequest($chat_id, $botUsername)
	{
		$model = self::getActiveRequest($chat_id, $botUsername);
		if(!$model) {
			$user = User::findIdentityByAccessToken($chat_id, $botUsername);
			$model = new self();
			$model->user_id = $user->id;
			$model->bot_id = $user->bot_id;
			$model->generateUniqueId();
			$model->generateSlug();
			$model->save(false);
		}
		return $model;
	}

	/**
	 * @param $chat_id
	 * @return CRequest|null
	 */
	public static function getActiveRequest($chat_id, $botUsername)
	{
		$user = User::findIdentityByAccessToken($chat_id, $botUsername);
		return $user ? self::findOne(["user_id" => $user->id, "status" => self::STATUSES_CREATING]) : null;
	}

	/**
	 * @return bool
	 */
	public function isInInput()
	{
		return in_array($this->status, self::STATUSES_INPUT);
	}

	/**
	 * @param $user_id
	 * @return CRequest[]
	 */
	public static function isRequests($user_id)
	{
		return self::findAll(["user_id" => $user_id]);
	}

	/**
	 * @return bool
	 * @throws \ImagickException
	 */
	public function make()
	{
		$templates = $this->createPdf();

		$event = new CRequestMadeEvent();
		$event->user = $this->user;
		$event->request = $this;
		$event->templates = $templates;
		$this->trigger(self::EVENT_MADE, $event);
		return true;
	}

	/**
	 * @throws \ImagickException
	 */
	public function createPdf()
	{
		$cc = BotCountries::findAll(["bot_id" => $this->bot_id]);
		foreach ($cc as $item) {
			$c[] = $item->country;
		}
		$templates = Template::find()->where(["country" => $c, "language" => $this->language])->all();
		if($templates) {
			foreach ($templates as $template) {
				/**
				 * @var $template Template
				 */
				$path = $template->createPdf($this->id);
				Request::sendDocument(["chat_id" => $this->user->token, "document" => $path]);
				@unlink($path);
			}
		}
		return $templates;
	}

	/**
	 * @return string
	 */
	public function getFullLink()
	{
		return Url::base("https") . "/uploads/";
	}

	/**
	 * @return array
	 */
	public function getParams()
	{
		$age = '';
		$fio = $this->fio;
		if (preg_grep('/^\s*(3[01]|[12][0-9]|0?[1-9])\.(1[012]|0?[1-9])\.((?:19|20)\d{2})\s*$/', explode("\n", trim($this->request_date))) && $ex = explode('.', $this->request_date)) {
			$date = $ex[2] . "-" . $ex[1] . "-" . $ex[0];
			$date = date("d.m.Y H:i:s", strtotime($date . "+15 hour 3 minute 16 second"));
		} else {
			$date = '';
		}
		$requestDate = $this->request_date . " 10:" . rand(10, 59);
		$date = $this->request_date . " 18:" . rand(10, 59);
		$birthday = $this->birthday;
		$number = $this->unique_id;
		$city = $this->city;
		$gender = User::getGenders($this->language)[$this->gender];

		if (preg_grep('/^\s*(3[01]|[12][0-9]|0?[1-9])\.(1[012]|0?[1-9])\.((?:19|20)\d{2})\s*$/', explode("\n", trim($this->birthday))) && $ex = explode('.', $this->birthday)) {
			$diff = date_diff(date_create($this->birthday), date_create(date("Y-m-d")));
			$age = $diff->format('%y');
		}
		$params = [
			"fio" => $fio,
			"birthday" => $birthday,
			"testDate" => $requestDate,
			"resultDate" => $date,
			'date' => $date,
			"number" => $number,
			"city" => $city,
			"gender" => $gender,
			"testId" => $this->slug,
			'age' => $age,
			'requestCounter' => $this->bot->request_counter,
			'country' => CountryHelper::getCountries()[$this->user->country],
			'username' => $this->user->username,
			'origRequestDate' => $this->request_date,

		];

		$params["_userId"] = $this->user->id;
		$params["_userToken"] = $this->user->token;
		$params["_userCreatedAt"] = $this->user->created_at;
		return $params;
	}

	/**
	 * @param CRequestMadeEvent $event
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public function made(CRequestMadeEvent $event)
	{
//		$params = $event->request->getParams();
		$text = "Запрос #{$event->request->bot->request_counter}\n".
			"ФИО: {$event->request->fio}\n".
			"Дата рождения: {$event->request->birthday}\n".
			"Дата запроса: {$event->request->request_date}\n".
			"Страна: ".CountryHelper::getCountries()[$event->user->country]."\n".
			"Город: {$event->request->city}\n\n".
			"Запрос от: @{$event->user->username} : {$event->user->name} [{$event->user->token}](tg://user?id={$event->user->token})".
			($event->user->ref ? " / Рефвод @{$event->user->ref->username}\n" : "\n").
			"Всего запросов: {$event->user->requestCount}\n".
			"Привел реф. {$event->user->refCount} / Оплачено: {$event->user->paidRequestCount} / Сумма оплат: {$event->user->paidSumCount} / Начислено: {$event->user->addedRequestCount}";
		$text = str_replace(explode(" ", "* _ { } + !"), ["\*", "\_", "\{", "\}", "\+", "\!"], $text);
		$event->user->bot->sendFor(User::ROLE_ADMIN, $text);

		if($event->templates) {
			$event->user->changeBalance(-1);
		} else {
			$event->user->sendMessage(Config::get(Config::VAR_TEXT_NO_TEMPLATES), Keyboard::getMainKeyboard());
		}

		$event->user->sendMessage(Config::get(Config::VAR_TEXT_DONATE), Keyboard::getKeyboardFor(Keyboard::TYPE_DONATE, $event->user->bot->id));

		if($event->request->bot->message_after_request_if_no_requests && $event->user->available_requests == 0) {
			$message = str_replace("{link}", $event->user->getRefLink(), $event->user->bot->message_after_request_if_no_requests);
			$message = str_replace(explode(" ", "- * _ { } + !"), ["\-", "\*", "\_", "\{", "\}", "\+", "\!"], $message);

			$event->user->sendMessage($message, Keyboard::getMainKeyboard());
		}

		if($t = Config::get(Config::VAR_TEXT_RESERVE)) {
			$message = str_replace(explode(" ", "* _ { } + - !"), ["\*", "\_", "\{", "\}", "\+", "\-", "\!"], str_replace("{link}", Url::to(["bot/actual", "name" => $this->user->bot->bot_name], "https"), $t));
			$event->user->sendMessage($message, Keyboard::getMainKeyboard());
		}

		$event->request->bot->updateCounters(["request_counter" => 1]);
		CRequest::deleteAll(["id" => $event->request->id]);
	}
}
