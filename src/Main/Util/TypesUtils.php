<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Main\Util;

use Hesper\Core\Base\StaticFactory;

/**
 * Class TypesUtils
 * @package Hesper\Main\Util
 */
final class TypesUtils extends StaticFactory {

	const SIGNED_MAX   = 2147483647;
	const UNSIGNED_MAX = 4294967295;

	public static function signedToUnsigned($signedInt) {
		if ($signedInt < 0) {
			return $signedInt + self::UNSIGNED_MAX + 1;
		} else {
			return $signedInt;
		}
	}

	public static function unsignedToSigned($unsignedInt) {
		if ($unsignedInt > self::SIGNED_MAX) {
			return $unsignedInt - self::UNSIGNED_MAX - 1;
		} else {
			return $unsignedInt;
		}
	}
}
