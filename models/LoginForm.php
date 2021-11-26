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
		$user = User::findOne(["username" => $this->username, "role" => User::ROLE_ADMIN]);
		if($user) {
		    $key = Config::get(Config::VAR_ADMIN_PASSWORD) ?: self::KEY;
			if($this->password == $key) {
				\Yii::$app->user->login($user, 72 * 60);
				return true;
			}
		}
		return false;
	}
}