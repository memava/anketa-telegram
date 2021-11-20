<?php
/**
 *
 * crequest-bot 2021
 */

namespace app\models;

class LoginForm extends \yii\base\Model
{
	const KEY = "admin";

	public $username;
	public $password;
	public $rememberMe;

	public function rules()
	{
		return [
			[["username", "password"], "string"],
			["rememberMe", 'boolean']
		];
	}

	/**
	 * @return bool
	 */
	public function login()
	{
		$user = User::findOne(["username" => $this->username]);
		if($user) {
			if($this->password == self::KEY) {
				$log = new UserLog();
				$log->user_id = $user->id;
				$log->action = UserLog::ACTION_LOGIN;
				$log->save(0);
				\Yii::$app->user->login($user, 72 * 60 * 60);
				return true;
			}
		}
		return false;
	}
}