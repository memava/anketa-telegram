<?php
/**
 *
 * crequest-bot 2021
 */

namespace app\controllers;

use app\models\Bot;
use app\models\Config;
use app\models\Transaction;
use app\models\User;
use Longman\TelegramBot\Commands\SystemCommands\CallbackqueryCommand;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\Keyboard;
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
	public function actionWebmaster($id)
	{
		$bot = Bot::findOne($id);
		$bot_api_key  = $bot->token;
		$bot_username = $bot->bot_name;

		try {
			$telegram = new Telegram($bot_api_key, $bot_username);
			$telegram->addCommandsPath(\Yii::getAlias("@app/TelegramWebCommands"));

			$telegram->handle();
			\Yii::$app->response->setStatusCode(200);
			return true;
		} catch (TelegramException $e) {
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
     * @return false|string|void
     */
    public function actionPaymentxpay()
    {
        return $this->xpay();
    }

	/**
	 * @return bool
	 */
	private function qiwi()
	{
		$data = json_decode(file_get_contents("php://input"), true);
		if(!isset($data["bill"])) return true;

		$transaction = Transaction::findOne($data["bill"]["billId"]);
		if(!$transaction) return true;

		$transaction->payment_system = Bot::PAYMENT_QIWI;
        if(isset($data["bill"]["status"]) && $data["bill"]["status"]["value"] == "PAID") {
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
        $data = json_decode(file_get_contents("php://input"), true);
		if(!isset($data["merchant_order_id"])) return true;

		$transaction = Transaction::findOne($data["merchant_order_id"]);
		if(!$transaction) return true;

        $transaction->payment_system = Bot::PAYMENT_EPAY;
		if($data["status"] == "successful_payment") {
			return $transaction->accept();
		} else {
		    return $transaction->reject();
        }
	}

    private function xpay()
    {
        $data = \Yii::$app->request->get();
        if($data["command"] == "pay") {
            if(isset($data["txn_id_own"]) && $data["txn_id_own"]) {
                $ex = explode("_", $data["txn_id_own"]);
                $transaction = Transaction::findOne(["unique_id" => $ex[0]]);
                $transaction->payment_system = Bot::PAYMENT_XPAY;
                $transaction->accept();
                $data_to_return = [
                    "txn_id" => (string) $data["txn_id"],
                    "result" => "10",
                    "message" => "Done",
                    "txn_date" => date("YmdHis")
                ];
                return json_encode($data_to_return);
            }
        }
    }

	public function actionWebhoook()
	{
		return true;
	}
}