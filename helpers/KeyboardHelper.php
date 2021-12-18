<?php
/**
 *
 * crequest-bot 2021
 */

namespace app\helpers;

class KeyboardHelper
{
	const BTN_MY_PROFILE = "\xF0\x9F\x91\xBB Мой профиль";
	const BTN_CREATE_REQUEST = "\xF0\x9F\x93\x8B Создать запрос";
	const BTN_CHANGE_COUNTRY = "Изменить страну";
	const BTN_DONATE = "\xF0\x9F\x92\xB3 Пополнить балланс";
	const BTN_MAIN_MENU = "\xF0\x9F\x8F\xA0 Главное меню";
	const BTN_FAQ = "Частые вопросы";

	const BUTTONS = [
		self::BTN_MY_PROFILE,
		self::BTN_CREATE_REQUEST,
		self::BTN_CHANGE_COUNTRY,
		self::BTN_DONATE,
		self::BTN_MAIN_MENU,
        self::BTN_FAQ
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
