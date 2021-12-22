<?php

namespace app\models;

use app\events\BalanceChangedEvent;
use GuzzleHttp\Client;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property int|null $type
 * @property int|null $user_id
 * @property int|null $balance_before
 * @property int|null $balance_after
 * @property int|null $sum
 * @property int|null $sum_rub
 * @property int|null $sum_uah
 * @property int|null $currency
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $unique_id
 * @property int|null $payment_system
 * @property string $link
 * @property-read int $amount
 *
 * @property-read User $user
 * @property-read Bot $bot
 */
class Transaction extends \yii\db\ActiveRecord
{
	const TYPE_MANUAL = 1;
	const TYPE_PAYMENT = 2;

	const STATUS_NEW = 1;
	const STATUS_REJECT = 0;
	const STATUS_END = 10;

	const CURRENCY_UAH = 1;
	const CURRENCY_RUB = 2;
	const CURRENCY_BONUS = 3;


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
        return 'transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'user_id', 'balance_before', 'balance_after', 'sum_rub', 'sum_uah', 'sum', 'unique_id', 'currency', 'status', 'created_at', 'updated_at'], 'integer'],
			[["link"], "string"]
        ];
    }

	/**
	 * @return int|null
	 */
	public function getSum()
	{
		return $this->sum_rub ?: $this->sum_uah;
	}

	/**
	 * @param $v
	 */
	public function setSum($v)
	{
		$this->sum_rub = $v;
	}

	/**
	 * @return string[]
	 */
	public static function getStatuses()
	{
		return [
			self::STATUS_NEW => "Новый",
			self::STATUS_END => "Оплачен",
			self::STATUS_REJECT => "Отклонен",
		];
	}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'user_id' => 'User ID',
            'balance_before' => 'Balance Before',
            'balance_after' => 'Balance After',
            'sum' => 'Сумма',
            'currency' => 'Currency',
            'status' => 'Статус',
            'created_at' => 'Создан',
            'updated_at' => 'Updated At',
        ];
    }

	/**
	 * @param bool $runValidation
	 * @param null $attributeNames
	 * @return bool
	 */
	public function save($runValidation = true, $attributeNames = null)
	{
		if($this->isNewRecord) {
			$this->unique_id = rand(100000000, 999999999);
		}
		if(parent::save($runValidation, $attributeNames)) {
			if($this->status == self::STATUS_END) {
				$event = new BalanceChangedEvent();
				$event->user = $this->user;
				$event->oldBalance = $this->balance_before;
				$event->newBalance = $this->balance_after;
				$event->type = $this->type;
				$event->sum = $this->sum_rub;
				$this->user->trigger(User::EVENT_BALANCE_CHANGED, $event);
			}
			return true;
		}
		return false;
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
		return $this->hasOne(Bot::class, ["id" => "user.bot_id"]);
	}

    /**
     * @return mixed
     */
    private function makeLinkQiwi()
    {
        $currency = $this->currency == self::CURRENCY_UAH ? "UAH" : "RUB";
        $params = [
            'publicKey' => Config::get(Config::VAR_QIWI_PUBLIC_KEY),
            'amount' => $this->sum,
            'billId' => $this->id,
            'currency' => $currency,
        ];

        Yii::$app->qiwi->key = Config::get(Config::VAR_QIWI_PRIVATE_KEY);
        return Yii::$app->qiwi->createPaymentForm($params);
    }

    /**
     * @return string
     */
    private function makeLinkEpay()
    {
        $ex = explode(".", Yii::$app->request->hostName);
        $domain = $ex[count($ex)-2] . "." . $ex[count($ex)-1];
        return "https://donate.".$domain."/transaction/donate?d=".$this->unique_id;
    }

    /**
     * @return string
     */
    private function makeLinkGlobal()
    {
        $ex = explode(".", Yii::$app->request->hostName);
        $domain = $ex[count($ex)-2] . "." . $ex[count($ex)-1];
        $data = [
            "walletId" => Config::get(Config::VAR_GLOBAL24_KEY),
            "cardAmount" => $this->sum_uah * 100,
            "lang" => "ru",
            "callbackUrl" => "https://donate.".$domain."/transaction/success?d=".$this->unique_id,
            "quittanceDest" => "noemail@gmail.com",
            "comment" => "Оплата {$this->sum_uah} грн ($this->unique_id)",
            "blocked" => 1
        ];
        return "https://global24.pro/wid/c2w/?".http_build_query($data);
    }

    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function makeLinkXpay()
    {
        $privateKey = Config::get(Config::VAR_XPAY_OUR_PRIVATE_KEY);
        $publicKey = Config::get(Config::VAR_XPAY_THEIR_PUBLIC_KEY);
        $cryptManager = new CryptManager($privateKey, $publicKey);

        $requestData = [
            "Email" => Config::get(Config::VAR_XPAY_EMAIL_FOR_PAYMENT),
            "PaymentSum" => $this->sum_uah * 100,
            "CallBackURL" => Url::to(["api/paymentxpay"], "https"),
            "Transaction" => [
                "TransactionID" => (string) $this->unique_id . "_" . $this->sum_uah
            ],
            "PaymentInfo" => [
                [
                    "Caption" => "Назначение",
                    "Value" => "Оплата за запросы: $this->amount шт."
                ]
            ]
        ];

        $partner = [
            'PartnerToken' => Config::get(Config::VAR_XPAY_PARTNER_TOKEN),
            'OperationType' => 10005,
        ];

        $data = [
            'Partner' => $partner,
            'Data' => $cryptManager->encrypt(json_encode($requestData)),
            'KeyAES' => $cryptManager->getEncryptedAESKey(),
            'Sign' => $cryptManager->getSignedKey(),
        ];

        $client = new Client();
        $post = $client->post( "https://stage-papi.xpay.com.ua:488/xpay", ["body" => json_encode($data)]);
        $response = (string) $post->getBody();
        //return $response;
        return json_decode($response, true)["Data"]["URI"];
    }

	/**
	 * @return string
	 */
	public function getPaymentLink()
	{
        $link_qiwi = $this->makeLinkQiwi();
        $link_epay = $this->makeLinkEpay();
        $link_global24 = $this->makeLinkGlobal();

        $link_xpay = $this->makeLinkXpay();

        $this->link = json_encode(["link_epay" => $link_epay, "link_qiwi" => $link_qiwi, 'link_global24' => $link_global24, 'link_xpay' => $link_xpay]);
        $this->save(false);

		return $this->link;
	}

	/**
	 * @param User $user
	 * @param $request
	 * @param int $sum
	 * @param int $type
	 * @param int $currency
	 * @return bool|string
	 */
	public static function changeBalance(User $user, $request, $sum = 0, $sum_rub = 0, $type = self::TYPE_MANUAL, $currency = self::CURRENCY_BONUS)
	{
		if(!$transaction = Transaction::findOne(["user_id" => $user->id, "type" => self::TYPE_PAYMENT, "status" => self::STATUS_NEW])) {
			$transaction = new self();
		}
		$transaction->status = self::STATUS_NEW;
		$transaction->user_id = $user->id;
		$transaction->balance_before = $user->available_requests;
		$transaction->balance_after = $request;
		$transaction->type = $type;
		$transaction->currency = $currency;
		if ($type != self::TYPE_MANUAL) {
			if($transaction->currency == self::CURRENCY_UAH) {
				if($transaction->sum_uah != $sum || !$transaction->link) {
					$transaction->sum_uah = $sum;
				}
			}
			$transaction->sum_rub = $sum_rub;
		} else {
			$transaction->status = self::STATUS_END;
		}
		if ($transaction->save(false)) {
			if ($type == self::TYPE_MANUAL) return true;

			$transaction->getPaymentLink();
			return json_decode($transaction->link, true);
		}
	}

	/**
	 * @return float|int
	 */
	public function getAmount()
	{
		return abs($this->balance_after - $this->balance_before);
	}

	/**
	 * @return bool
	 */
	public function accept()
	{
		$this->status = self::STATUS_END;
		//$this->user->changeBalance($this->amount);
		return $this->save(false);
	}

	/**
	 * @return bool
	 */
	public function reject()
	{
		$this->status = self::STATUS_REJECT;
		return $this->save(false);
	}

    /**
     * @param $userId
     * @return bool|int|string|null
     */
    public static function isTransaction($userId)
    {
        return self::find()->where(["user_id" => $userId])->andWhere("balance_after < balance_before")->count();
    }
}
