<?php
/**
 *
 * crequest-bot 2021
 */

namespace app\controllers;

use app\models\Bot;
use app\models\Config;
use app\models\Transaction;
use Longman\TelegramBot\Commands\SystemCommands\CallbackqueryCommand;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use yii\web\Controller;

class ApiController extends Controller
{
	public $enableCsrfValidation = false;

	/**
	 * @param $id
	 * @return bool|void
	 */
	public function actionWebhook($id)
	{
		$bot = Bot::findOne($id);
		$bot_api_key  = $bot->token;
		$bot_username = $bot->bot_name;

		try {
			// Create Telegram API object
			$telegram = new Telegram($bot_api_key, $bot_username);
			$telegram->addCommandsPath(\Yii::getAlias("@app/TelegramCommands"));

//			$telegram->addCommandClass(TestCommand::class);
			// Handle telegram webhook request
			$telegram->handle();
			\Yii::$app->response->setStatusCode(200);
			return true;
		} catch (TelegramException $e) {
			// Silence is golden!
			// log telegram errors
			 echo $e->getMessage();
		}
	}

	/**
	 * @param $id
	 * @return bool|void
	 */
	public function actionPayment($id)
	{
		if($id == Bot::PAYMENT_QIWI) {
			return $this->qiwi();
		} else if($id == Bot::PAYMENT_EPAY) {
			return $this->epay();
		}
	}

	/**
	 * @return bool
	 */
	private function qiwi()
	{
		$data = json_decode(file_get_contents("php://input"), true);
		if(!isset($data["bill"])) return true;

		$transaction = Transaction::findOne($data["bill"]["billId"]);
		if($transaction && isset($data["bill"]["status"]) && $data["bill"]["status"]["value"] == "PAID") {
			return $transaction->accept();
		} else {
			return $transaction->reject();
		}
		//return $data["bill"]["billId"];
	}

	/**
	 * @return bool|void
	 */
	private function epay()
	{
		$data = $_POST;
		if(!isset($data["merchant_order_id"])) return true;

		$transaction = Transaction::findOne($data["merchant_order_id"]);
		if($transaction && $data["status"] == "successful_payment") {
			return $transaction->accept();
		}
	}

	public function actionWebhoook()
	{
		return true;
	}
}