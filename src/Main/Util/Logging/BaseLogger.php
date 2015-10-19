<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Util\Logging;

/**
 * Class BaseLogger
 * @package Hesper\Main\Util\Logging
 */
abstract class BaseLogger {

	private $level = null;

	/**
	 * @return BaseLogger
	 **/
	abstract protected function publish(LogRecord $record);

	/**
	 * @return BaseLogger
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
	 * @return BaseLogger
	 **/
	final public function log(LogLevel $level, $message) {
		$this->logRecord(LogRecord::create()
		                          ->setLevel($level)
		                          ->setMessage($message));

		return $this;
	}

	/**
	 * @return BaseLogger
	 **/
	final public function logRecord(LogRecord $record) {
		$levelMatches = $this->level === null || $record->getLevel()
		                                                ->getId() <= $this->level->getId();

		if ($levelMatches && $this->isLoggable($record)) {
			$this->publish($record);
		}

		return $this;
	}

	/**
	 * you may override me
	 **/
	protected function isLoggable(LogRecord $record) {
		return true;
	}

	/**
	 * @return BaseLogger
	 **/
	final public function severe($message) {
		$this->log(LogLevel::severe(), $message);

		return $this;
	}

	/**
	 * @return BaseLogger
	 **/
	final public function warning($message) {
		$this->log(LogLevel::warning(), $message);

		return $this;
	}

	/**
	 * @return BaseLogger
	 **/
	final public function info($message) {
		$this->log(LogLevel::info(), $message);

		return $this;
	}

	/**
	 * @return BaseLogger
	 **/
	final public function config($message) {
		$this->log(LogLevel::config(), $message);

		return $this;
	}

	/**
	 * @return BaseLogger
	 **/
	final public function fine($message) {
		$this->log(LogLevel::fine(), $message);

		return $this;
	}

	/**
	 * @return BaseLogger
	 **/
	final public function finer($message) {
		$this->log(LogLevel::finer(), $message);

		return $this;
	}

	/**
	 * @return BaseLogger
	 **/
	final public function finest($message) {
		$this->log(LogLevel::finest(), $message);

		return $this;
	}
}
