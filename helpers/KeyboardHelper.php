<?php
/**
 *
 * crequest-bot 2021
 */

namespace app\helpers;

class KeyboardHelper
{
	const BTN_MY_PROFILE = "Мой профиль";
	const BTN_CREATE_REQUEST = "Создать запрос";
	const BTN_CHANGE_COUNTRY = "Сменить страну";
	const BTN_DONATE = "Поддержать бота / Donate";
	const BTN_MAIN_MENU = "Главное меню";
	const BTN_PAY = "Пополнить балланс";

	const BUTTONS = [
		self::BTN_MY_PROFILE,
		self::BTN_CREATE_REQUEST,
		self::BTN_CHANGE_COUNTRY,
		self::BTN_DONATE,
		self::BTN_PAY,
		self::BTN_MAIN_MENU
	];

	/**
	 * @param $btn
	 * @return string
	 */
	public static function getRxFor($btn)
	{
		return '/^'.$btn.'$/u';
	}

	/**
	 * @return string
	 */
	public static function getRxForAll()
	{
		return '/^'.str_replace(["/"], ["\/"],implode('|', self::BUTTONS)).'$/u';
	}
}
