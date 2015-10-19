<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Vladimir A. Altuchov
 */
namespace Hesper\Main\Util\IO;

use Hesper\Core\Exception\BaseException;
use Hesper\Core\Exception\IOException;
use Hesper\Core\Exception\WrongStateException;

/**
 * Class FileReader
 * @package Hesper\Main\Util\IO
 */
final class FileReader extends Reader {

	private $fd = null;

	/**
	 * @return FileReader
	 **/
	public static function create($fileName) {
		return new self($fileName);
	}

	/**
	 * @return FileReader
	 **/
	public function __construct($fileName) {
		if (!is_readable($fileName)) {
			throw new WrongStateException("Can not read {$fileName}");
		}

		try {
			$this->fd = fopen($fileName, 'rt');
		} catch (BaseException $e) {
			throw new IOException($e->getMessage());
		}
	}

	public function __destruct() {
		try {
			$this->close();
		} catch (BaseException $e) {
			// boo.
		}
	}

	public function isEof() {
		return feof($this->fd);
	}

	public function markSupported() {
		return true;
	}

	/**
	 * @return FileReader
	 **/
	public function mark() {
		$this->mark = ftell($this->fd);

		return $this;
	}

	/**
	 * @return FileReader
	 **/
	public function reset() {
		if (fseek($this->fd, $this->mark) < 0) {
			throw new IOException('mark has been invalidated');
		}

		return $this;
	}

	/**
	 * @return FileReader
	 **/
	public function close() {
		if (!fclose($this->fd)) {
			throw new IOException('failed to close the file');
		}

		return $this;
	}

	public function read($length) {
		$result = null;

		for ($i = 0; $i < $length; $i++) {
			if (($char = fgetc($this->fd)) === false) {
				break;
			}

			$result .= $char;
		}

		return $result;
	}
}
