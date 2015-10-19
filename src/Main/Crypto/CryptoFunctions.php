<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Crypto;

use Hesper\Core\Base\StaticFactory;

/**
 * Class CryptoFunctions
 * @package Hesper\Main\Crypto
 */
final class CryptoFunctions extends StaticFactory {

	const SHA1_BLOCK_SIZE = 64;

	/**
	 * @see http://tools.ietf.org/html/rfc2104
	 **/
	public static function hmacsha1($key, $message) {
		if (strlen($key) > self::SHA1_BLOCK_SIZE) {
			$key = sha1($key, true);
		}

		$key = str_pad($key, self::SHA1_BLOCK_SIZE, "\x00", STR_PAD_RIGHT);

		$ipad = null;
		$opad = null;
		for ($i = 0; $i < self::SHA1_BLOCK_SIZE; $i++) {
			$ipad .= "\x36" ^ $key[$i];
			$opad .= "\x5c" ^ $key[$i];
		}

		return sha1($opad . sha1($ipad . $message, true), true);
	}
}
