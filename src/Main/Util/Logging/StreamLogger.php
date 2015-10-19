<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Util\Logging;

use Hesper\Core\Exception\BaseException;
use Hesper\Main\Util\IO\OutputStream;

/**
 * Class StreamLogger
 * @package Hesper\Main\Util\Logging
 */
final class StreamLogger extends BaseLogger {

	private $stream = null;

	public function __destruct() {
		try {
			$this->close();
		} catch (BaseException $e) {
			// boo.
		}
	}

	/**
	 * @return StreamLogger
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return OutputStream
	 **/
	public function getOutputStream() {
		return $this->stream;
	}

	/**
	 * @return StreamLogger
	 **/
	public function setOutputStream(OutputStream $stream) {
		$this->stream = $stream;

		return $this;
	}

	/**
	 * @return StreamLogger
	 **/
	public function flush() {
		if ($this->stream) {
			$this->stream->flush();
		}

		return $this;
	}

	/**
	 * @return StreamLogger
	 **/
	public function close() {
		if ($this->stream) {

			$this->flush();
			$this->stream->close();

			$this->stream = null;
		}

		return $this;
	}

	/**
	 * @return StreamLogger
	 **/
	protected function publish(LogRecord $record) {
		if (!$this->stream) {
			return $this;
		}

		$this->stream->write($record->toString() . "\n");

		return $this;
	}
}
