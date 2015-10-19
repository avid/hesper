<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov, Denis M. Gabaidulin
 */
namespace Hesper\Main\Util\Logging;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Timestamp;

/**
 * Class LogRecord
 * @package Hesper\Main\Util\Logging
 */
final class LogRecord {

	private $message = null;
	private $level   = LogLevel::INFO;

	private $date = null;

	public function __construct() {
		$this->date = Timestamp::makeNow();
	}

	/**
	 * @return LogRecord
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return LogRecord
	 **/
	public function setMessage($message) {
		Assert::isString($message);

		$this->message = $message;

		return $this;
	}

	public function getMessage() {
		return $this->message;
	}

	/**
	 * @return LogRecord
	 **/
	public function setDate(Timestamp $date) {
		$this->date = $date;

		return $this;
	}

	/**
	 * @return Timestamp
	 **/
	public function getDate() {
		return $this->date;
	}

	/**
	 * @return LogRecord
	 **/
	public function setLevel(LogLevel $level) {
		$this->level = $level;

		return $this;
	}

	/**
	 * @return LogLevel
	 **/
	public function getLevel() {
		return $this->level;
	}

	/**
	 * returns message in human readable form, ex:
	 * Jul  7 07:07:07 warning: all your base are belong to us
	 **/
	public function toString() {
		return sprintf('%s %2s %s %s: %s', date('M', $this->date->toStamp()), $this->date->getDay(), $this->date->toTime(':', ':'), $this->level->getName(), $this->message);
	}
}
