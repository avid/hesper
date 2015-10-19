<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Net\Mail;

use Hesper\Core\Base\Enumeration;

/**
 * Class MailEncoding
 * @package Hesper\Main\Net\Mail
 */
final class MailEncoding extends Enumeration {

	const SEVEN_BITS = 0x01;
	const EIGHT_BITS = 0x02;
	const BASE64     = 0x03;
	const QUOTED     = 0x04;

	protected $names = [self::SEVEN_BITS => '7bit', self::EIGHT_BITS => '8bit', self::BASE64 => 'base64', self::QUOTED => 'quoted-printable'];

	/**
	 * @return MailEncoding
	 **/
	public static function seven() {
		return new self(self::SEVEN_BITS);
	}

	/**
	 * @return MailEncoding
	 **/
	public static function eight() {
		return new self(self::EIGHT_BITS);
	}

	/**
	 * @return MailEncoding
	 **/
	public static function base64() {
		return new self(self::BASE64);
	}

	/**
	 * @return MailEncoding
	 **/
	public static function quoted() {
		return new self(self::QUOTED);
	}
}
