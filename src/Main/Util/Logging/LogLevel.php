<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Util\Logging;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Enumeration;

/**
 * Class LogLevel
 * @package Hesper\Main\Util\Logging
 */
final class LogLevel extends Enumeration {

	const SEVERE  = 1; // highest value
	const WARNING = 2;
	const INFO    = 3;
	const CONFIG  = 4;
	const FINE    = 5;
	const FINER   = 6;
	const FINEST  = 7; // lowest value

	protected $names = [self::SEVERE => 'severe', self::WARNING => 'warning', self::INFO => 'info', self::CONFIG => 'config', self::FINE => 'fine', self::FINER => 'finer', self::FINEST => 'finest'];

	/**
	 * @return LogLevel
	 **/
	public function setId($id) {
		Assert::isNull($this->id, 'i am immutable one!');

		return parent::setId($id);
	}

	/**
	 * @return LogLevel
	 **/
	public static function severe() {
		return self::getInstance(self::SEVERE);
	}

	/**
	 * @return LogLevel
	 **/
	public static function warning() {
		return self::getInstance(self::WARNING);
	}

	/**
	 * @return LogLevel
	 **/
	public static function info() {
		return self::getInstance(self::INFO);
	}

	/**
	 * @return LogLevel
	 **/
	public static function config() {
		return self::getInstance(self::CONFIG);
	}

	/**
	 * @return LogLevel
	 **/
	public static function fine() {
		return self::getInstance(self::FINE);
	}

	/**
	 * @return LogLevel
	 **/
	public static function finer() {
		return self::getInstance(self::FINER);
	}

	/**
	 * @return LogLevel
	 **/
	public static function finest() {
		return self::getInstance(self::FINEST);
	}

	/**
	 * @return LogLevel
	 **/
	private static function getInstance($id) {
		static $instances = [];

		if (!isset($instances[$id])) {
			$instances[$id] = new self($id);
		}

		return $instances[$id];
	}
}
