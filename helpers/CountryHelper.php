<?php
/**
 *
 * crequest-bot 2021
 */

namespace app\helpers;

use app\models\Bot;
use app\models\CRequest;
use Longman\TelegramBot\Entities\InlineKeyboard;

class CountryHelper
{
	const COUNTRY_UKRAINE = 1;
	const COUNTRY_RUSSIA = 2;
	const COUNTRY_KAZAKHSTAN = 3;
	const COUNTRY_BELARUS = 4;

	const COUNTRIES = [
		self::COUNTRY_UKRAINE,
		self::COUNTRY_RUSSIA,
		self::COUNTRY_KAZAKHSTAN,
		self::COUNTRY_BELARUS,
	];

	/**
	 * @return string[]
	 */
	public static function getCountries()
	{
		return [
			self::COUNTRY_UKRAINE => "Украина",
			self::COUNTRY_RUSSIA => "Россия",
			self::COUNTRY_KAZAKHSTAN => "Казахстан",
			self::COUNTRY_BELARUS => "Беларусь"
		];
	}

	/**
	 * @param $name
	 * @return false|mixed
	 */
	public static function getCountryByName($name)
	{
		return key(array_filter(self::getCountries(), function ($v) use ($name) {
			return md5(trim($v)) == md5(trim($name));
		}));
	}

	/**
	 * @return InlineKeyboard
	 */
	public static function getKeyboard(): InlineKeyboard
	{
		return self::getKeyboardFor([]);
	}

	/**
	 * @param array $countriesFilter
	 * @return InlineKeyboard
	 */
	public static function getKeyboardFor($countriesFilter = [])
	{
		$countries = CountryHelper::getCountries();
		if($countriesFilter)
			$countries = array_filter($countries, function ($k) use ($countriesFilter){
				return in_array($k, $countriesFilter);
			}, ARRAY_FILTER_USE_KEY);
		$countriesNew = [];
		foreach ($countries as $key => $country) {
			$countriesNew[][0] = ["text" => $country, "callback_data" => "/selectcountry ".$key];
		}
		$keyboard = new InlineKeyboard(...$countriesNew);
		return $keyboard;
	}

	/**
	 * @param $country
	 * @return array
	 */
	public static function getCountryLanguages($country)
	{
		$cl = [
			self::COUNTRY_UKRAINE => [
				CRequest::LANGUAGE_UA,
				CRequest::LANGUAGE_EN,
			],
			self::COUNTRY_RUSSIA => [
				CRequest::LANGUAGE_RU,
				CRequest::LANGUAGE_EN,
			],
			self::COUNTRY_KAZAKHSTAN => [
				CRequest::LANGUAGE_RU,
				CRequest::LANGUAGE_EN,
			],
			self::COUNTRY_BELARUS => [
				CRequest::LANGUAGE_RU,
				CRequest::LANGUAGE_EN,
			],
		];
		return $cl[$country];
	}

	/**
	 * @param $country
	 * @return string[]
	 */
	public static function getCountryCities($country)
	{
		$ukraine = [
			"Київ",
			"Одеса",
			"Дніпро",
			"Вінниця",
			"Луцьк",
			"Житомир",
			"Ужгород",
			"Запоріжжя",
			"Івано-Франківськ",
			"Біла Церква",
			"Кропивницький",
			"Львів",
			"Миколаїв",
			"Полтава",
			"Суми",
			"Тернопіль",
			"Харків",
			"Херсон",
			"Хмельницький",
			"Черкаси",
			"Чернівці",
			"Чернігів"
		];
		return $ukraine;
	}

	/**
	 * @return string[]
	 */
	public static function getShortName()
	{
		return [
			self::COUNTRY_UKRAINE => "UA",
			self::COUNTRY_RUSSIA => "RU",
			self::COUNTRY_BELARUS => "BL",
			self::COUNTRY_KAZAKHSTAN => "KZ",
		];
	}
}