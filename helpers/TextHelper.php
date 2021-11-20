<?php
/**
 *
 * pcr-bot 2021
 */

namespace app\helpers;

class TextHelper
{
	/**
	 * @param $text
	 * @param array $data
	 * @return array|mixed|string|string[]|null
	 */
	public static function replace($text, $data = [])
	{
		foreach ($data as $item => $value) {
			$text = preg_replace('/{'.$item.'}/', $value, $text);
		}
		return $text;
	}
}