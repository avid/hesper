<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Vladimir A. Altuchov
 */
namespace Hesper\Main\Net\Ip;

use Hesper\Core\Base\Stringable;
use Hesper\Core\DB\Dialect;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\OSQL\DialectString;
use Hesper\Main\Util\TypesUtils;

/**
 * @ingroup Ip
 **/
class IpAddress implements Stringable, DialectString {

	private $longIp = null;

	/**
	 * @return IpAddress
	 **/
	public static function create($ip) {
		return new self($ip);
	}

	public static function createFromCutted($ip) {
		if (substr_count($ip, '.') < 3) {
			return self::createFromCutted($ip . '.0');
		}

		return self::create($ip);
	}

	public function __construct($ip) {
		$this->setIp($ip);
	}

	/**
	 * @return IpAddress
	 **/
	public function setIp($ip) {
		$long = ip2long($ip);

		if ($long === false) {
			throw new WrongArgumentException('wrong ip given');
		}

		$this->longIp = $long;

		return $this;
	}

	public function getLongIp() {
		return $this->longIp;
	}

	public function toString() {
		return long2ip($this->longIp);
	}

	public function toDialectString(Dialect $dialect) {
		return $dialect->quoteValue($this->toString());
	}

	public function toSignedInt() {
		return TypesUtils::unsignedToSigned($this->longIp);
	}
}
