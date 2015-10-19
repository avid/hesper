<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 */
namespace Hesper\Main\Crypto;

use Hesper\Core\Base\StaticFactory;
use Hesper\Core\Exception\SecurityException;

class Password extends StaticFactory {

	/**
	 * @param string $password
	 * @return bool|string
	 */
	public static function bCryptHash($password) {
		return password_hash($password, PASSWORD_BCRYPT);
	}

	/**
	 * @param string $hash
	 * @param string $password
	 * @return bool
	 */
	public static function bCryptVerify($hash, $password) {
		return password_verify($password, $hash);
	}

	/**
	 * @param string $hash
	 * @param string $password
	 * @throws SecurityException
	 */
	public static function bCryptAssert($hash, $password) {
		if( !self::bCryptVerify($hash, $password) ) {
			throw new SecurityException('Hash and password mismatch');
		}
	}

}