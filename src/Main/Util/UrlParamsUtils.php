<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Alexey S. Denisov
 */
namespace Hesper\Main\Util;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\StaticFactory;

/**
 * Class UrlParamsUtils
 * @package Hesper\Main\Util
 */
final class UrlParamsUtils extends StaticFactory {

	/**
	 * @deprecated to support old convert method in CurlHttpClient
	 *
	 * @param array $array
	 *
	 * @return string
	 */
	public static function toStringOneDeepLvl($array) {
		Assert::isArray($array);
		$result = [];

		foreach ($array as $key => $value) {
			if (is_array($value)) {
				foreach ($value as $valueKey => $simpleValue) {
					$result[] = $key . '[' . $valueKey . ']=' . urlencode($simpleValue);
				}
			} else {
				$result[] = $key . '=' . urlencode($value);
			}
		}

		return implode('&', $result);
	}

	public static function toString($array) {
		$sum = function ($left, $right) {
			return $left . '=' . urlencode($right);
		};
		$params = self::toParamsList($array, true);

		return implode('&', array_map($sum, array_keys($params), $params));
	}

	public static function toParamsList($array, $encodeKey = false) {
		$result = [];

		self::argumentsToParams($array, $result, '', $encodeKey);

		return $result;
	}

	private static function argumentsToParams($array, &$result, $keyPrefix, $encodeKey = false) {
		foreach ($array as $key => $value) {
			$filteredKey = $encodeKey ? urlencode($key) : $key;
			$fullKey = $keyPrefix ? ($keyPrefix . '[' . $filteredKey . ']') : $filteredKey;

			if (is_array($value)) {
				self::argumentsToParams($value, $result, $fullKey, $encodeKey);
			} else {
				$result[$fullKey] = $value;
			}
		}
	}
}
