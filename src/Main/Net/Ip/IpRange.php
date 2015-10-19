<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Vladimir A. Altuchov
 */
namespace Hesper\Main\Net\Ip;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Stringable;
use Hesper\Core\DB\Dialect;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\OSQL\DialectString;
use Hesper\Main\Base\SingleRange;

/**
 * Class IpRange
 * @package Hesper\Main\Net\Ip
 */
class IpRange implements SingleRange, DialectString, Stringable {

	const MASK_MAX_SIZE = 31;

	const SINGLE_IP_PATTERN = '/^(\d{1,3}\.){3}\d{1,3}$/';
	const INTERVAL_PATTERN  = '/^\d{1,3}(\.\d{1,3}){3}\s*-\s*\d{1,3}(\.\d{1,3}){3}$/';
	const IP_SLASH_PATTERN  = '/^(\d{1,3}\.){0,3}\d{1,3}\/\d{1,2}$/';

	private $startIp = null;
	private $endIp   = null;

	/**
	 * @return IpRange
	 **/
	public static function create(/**/) {
		/**
		 * @todo WTF? here need use ReflectionClass::newInstanceArgs
		 */
		return new self(func_get_args());
	}

	public function __construct(/**/) {
		$args = func_get_args();

		if (count($args) == 1 && is_array($args[0])) {
			$args = $args[0];
		}

		if (count($args) == 2) //aka start and end
		{
			$this->setup($args[0], $args[1]);
		} elseif (count($args) == 1) { //start-end or ip/mask
			Assert::isString($args[0]);
			$this->createFromString($args[0]);

		} else {
			throw new WrongArgumentException('strange parameters received');
		}
	}

	/**
	 * @return IpAddress
	 **/
	public function getStart() {
		return $this->startIp;
	}

	/**
	 * @return IpRange
	 **/
	public function setStart(IpAddress $startIp) {
		$this->startIp = $startIp;

		return $this;
	}

	/**
	 * @return IpAddress
	 **/
	public function getEnd() {
		return $this->endIp;
	}

	/**
	 * @return IpRange
	 **/
	public function setEnd(IpAddress $endIp) {
		$this->endIp = $endIp;

		return $this;
	}

	public function contains(/* IpAddress */ $probe) {
		Assert::isInstance($probe, IpAddress::class);

		return (($this->startIp->getLongIp() <= $probe->getLongIp()) && ($this->endIp->getLongIp() >= $probe->getLongIp()));
	}

	public function toString() {
		return $this->startIp->toString() . '-' . $this->endIp->toString();
	}

	public function toDialectString(Dialect $dialect) {
		return $dialect->quoteValue($this->toString());
	}

	private function setup(IpAddress $startIp, IpAddress $endIp) {
		if ($startIp->getLongIp() > $endIp->getLongIp()) {
			throw new WrongArgumentException('start ip must be lower than ip end');
		}

		$this->startIp = $startIp;
		$this->endIp = $endIp;

		return $this;
	}

	private function createFromInterval($interval) {
		try {
			$parts = explode('-', $interval);

			$this->setup(IpAddress::create(trim($parts[0])), IpAddress::create(trim($parts[1])));

		} catch (\Exception $e) {
			throw new WrongArgumentException('strange parameters received');
		}

		return $this;
	}

	private function createFromSlash($ip, $mask) {
		$ip = IpAddress::createFromCutted($ip);

		if ($mask == 0 || self::MASK_MAX_SIZE < $mask) {
			throw new WrongArgumentException('wrong mask given');
		}

		$longMask = (int)(pow(2, (32 - $mask)) * (pow(2, $mask) - 1));

		if (($ip->getLongIp() & $longMask) != $ip->getLongIp()) {
			throw new WrongArgumentException('wrong ip network given');
		}

		$this->setup($ip, IpAddress::create(long2ip($ip->getLongIp() | ~$longMask)));
	}

	private function createFromString($string) {
		if (preg_match(self::SINGLE_IP_PATTERN, $string)) {
			$ip = IpAddress::create($string);
			$this->setup($ip, $ip);

		} elseif (preg_match(self::IP_SLASH_PATTERN, $string)) {
			list($ip, $mask) = explode('/', $string);
			$this->createFromSlash($ip, $mask);

		} elseif (preg_match(self::INTERVAL_PATTERN, $string)) {
			$this->createFromInterval($string);
		} else {
			throw new WrongArgumentException('strange parameters received');
		}
	}
}
