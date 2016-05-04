<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Net\Http;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\StaticFactory;

/**
 * Collection of static header functions.
 * @package Hesper\Main\Net\Http\
 */
final class HeaderUtils extends StaticFactory {

	private static $headerSent    = false;
	private static $redirectSent  = false;
	private static $cacheLifeTime = 3600;
	private static $headers       = [];

	public static function redirectRaw($url) {
		header("Location: {$url}");

		self::$headerSent = true;
		self::$redirectSent = true;
	}

	public static function redirectBack() {
		if (isset($_SERVER['HTTP_REFERER'])) {
			header("Location: {$_SERVER['HTTP_REFERER']}");
			self::$headerSent = true;
			self::$redirectSent = true;

			return $_SERVER['HTTP_REFERER'];
		}

		return false;
	}

	public static function getRequestHeaderList() {
		if (!empty(self::$headers)) {
			return self::$headers;
		}

		if (function_exists('apache_request_headers')) {
			self::$headers = apache_request_headers();
		} else {
			foreach ($_SERVER as $key => $value) {
				if (substr($key, 0, 5) == "HTTP_") {
					$name = self::extractHeader($key, "_", 5);
					self::$headers[$name] = $value;
				}
			}
		}

		return self::$headers;
	}

	public static function getRequestHeader($name) {
		$name = self::extractHeader($name, "-", 0);
		$list = self::getRequestHeaderList();

		if (isset($list[$name])) {
			return $list[$name];
		}

		return null;
	}

	public static function getParsedURI(/* ... */) {
		if ($num = func_num_args()) {
			$out = self::getURI();
			$uri = null;
			$arr = func_get_args();

			for ($i = 0; $i < $num; ++$i) {
				unset($out[$arr[$i]]);
			}

			foreach ($out as $key => $val) {
				if (is_array($val)) {
					foreach ($val as $k => $v) {
						$uri .= "&{$key}[{$k}]={$v}";
					}
				} else {
					$uri .= "&{$key}={$val}";
				}
			}

			return $uri;
		}

		return null;
	}

	public static function sendCachedHeader() {
		header('Cache-control: private, max-age=3600');

		header('Expires: ' . date('D, d M Y H:i:s', date('U') + self::$cacheLifeTime) . ' GMT');

		self::$headerSent = true;
	}

	public static function sendNotCachedHeader() {
		header('Cache-control: no-cache');
		header('Expires: ' . date('D, d M Y H:i:s', date('U') - self::$cacheLifeTime) . ' GMT');

		self::$headerSent = true;
	}

	public static function sendContentLength($length) {
		Assert::isInteger($length);

		header("Content-Length: {$length}");

		self::$headerSent = true;
	}

	public static function sendHttpStatus(HttpStatus $status) {
		header($status->toString());

		self::$headerSent = true;
	}

	public static function isHeaderSent() {
		return self::$headerSent;
	}

	public static function forceHeaderSent() {
		self::$headerSent = true;
	}

	public static function isRedirectSent() {
		return self::$redirectSent;
	}

	public static function setCacheLifeTime($cacheLifeTime) {
		self::$cacheLifeTime = $cacheLifeTime;
	}

	public static function getCacheLifeTime() {
		return self::$cacheLifeTime;
	}

	public static function getParsedAcceptLanguage() {
		$languages = [];
		preg_match_all(
			'/([a-z]{1,8}(?:-[a-z]{1,8})?)\s*(?:;\s*q\s*=\s*(1|0\.[0-9]+))?/i',
			isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '',
			$parsed
		);
		for($i=0;$i<count($parsed[0]);$i++) {
			$key = $parsed[1][$i];
			$value = $parsed[2][$i];
			if( !isset($languages[$key]) ) {
				$languages[$key] = ($value==='') ? 1 : floatval($value);
			}
		}
		arsort($languages, SORT_NUMERIC);
		return $languages;
	}

	private static function getURI() {
		$out = null;

		parse_str($_SERVER['QUERY_STRING'], $out);

		return $out;
	}

	private static function extractHeader($name, $delimiter, $length) {
		return str_replace(" ", "-", ucwords(strtolower(str_replace($delimiter, " ", $length ? substr($name, $length) : $name))));
	}
}
