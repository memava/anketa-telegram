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
    const COUNTRY_INDIA = 5;
    const COUNTRY_SRILANKA = 6;
    const COUNTRY_TURKEY = 7;
    const COUNTRY_EGYPT = 8;
    const COUNTRY_ISRAEL = 9;
    const COUNTRY_GREECE = 10;
    const COUNTRY_10 = 11;
    const COUNTRY_11 = 12;
    const COUNTRY_12 = 13;

	const COUNTRIES = [
		self::COUNTRY_UKRAINE,
		self::COUNTRY_RUSSIA,
		self::COUNTRY_KAZAKHSTAN,
		self::COUNTRY_BELARUS,
        self::COUNTRY_INDIA,
        self::COUNTRY_SRILANKA,
        self::COUNTRY_TURKEY,
        self::COUNTRY_EGYPT,
        self::COUNTRY_ISRAEL,
        self::COUNTRY_GREECE,
        self::COUNTRY_10,
        self::COUNTRY_11,
        self::COUNTRY_12,
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
			self::COUNTRY_BELARUS => "Беларусь",
            self::COUNTRY_INDIA => "India",
            self::COUNTRY_SRILANKA => "Sri-Lanka",
            self::COUNTRY_TURKEY => "Turkey",
            self::COUNTRY_EGYPT => "Egypt",
            self::COUNTRY_ISRAEL => "Israel",
            self::COUNTRY_GREECE => "Greece",
            self::COUNTRY_10 => "10",
            self::COUNTRY_11 => "11",
            self::COUNTRY_12 => "12",
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
				CRequest::LANGUAGE_UA,
				CRequest::LANGUAGE_EN,
			],
			self::COUNTRY_BELARUS => [
				CRequest::LANGUAGE_RU,
				CRequest::LANGUAGE_EN,
			],
		];
		return $cl[$country] ?? CRequest::DEFAULT_LANGUAGES;
	}

    /**
     * @param $country
     * @return string
     */
    public static function getCountryCurrency($country)
    {
        $cc = [
            self::COUNTRY_UKRAINE => " грн",
            self::COUNTRY_RUSSIA => " руб",
            self::COUNTRY_KAZAKHSTAN => " руб",
            self::COUNTRY_BELARUS => " руб"
        ];
        return $cc[$country] ?? " $";
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
			self::COUNTRY_BELARUS => "BY",
			self::COUNTRY_KAZAKHSTAN => "KZ",
            self::COUNTRY_INDIA => "IN",
            self::COUNTRY_SRILANKA => "LK",
            self::COUNTRY_TURKEY => "TR",
            self::COUNTRY_EGYPT => "EG",
            self::COUNTRY_ISRAEL => "IL",
            self::COUNTRY_GREECE => "GR",
            self::COUNTRY_10 => "10",
            self::COUNTRY_11 => "11",
            self::COUNTRY_12 => "12",
		];
	}
}