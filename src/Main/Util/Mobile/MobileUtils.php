<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Main\Util\Mobile;

use Hesper\Core\Base\StaticFactory;

/**
 * Class MobileUtils
 * @package Hesper\Main\Util\Mobile
 */
final class MobileUtils extends StaticFactory
{
	public static function extractIp(array $headers)
	{
		if (
			MobileRequestDetector::create()->isOperaMini($headers)
			&& isset($headers['HTTP_X_FORWARDED_FOR'])
		) {
			$ips = explode(',', $headers['HTTP_X_FORWARDED_FOR']);

			if ($ips)
				return trim($ips[count($ips) - 1]);
		} elseif (isset($headers['REMOTE_ADDR']))
			return $headers['REMOTE_ADDR'];

		return null;
	}

	public static function extractUserAgent(array $headers)
	{
		if (
			MobileRequestDetector::create()->isOperaMini($headers)
			&& isset($headers['HTTP_X_OPERAMINI_PHONE_UA'])
		)
			return $headers['HTTP_X_OPERAMINI_PHONE_UA'];
		elseif (isset($headers['HTTP_USER_AGENT']))
			return $headers['HTTP_USER_AGENT'];

		return null;
	}
}