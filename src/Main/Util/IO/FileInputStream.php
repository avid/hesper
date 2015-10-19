<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Util\IO;

use Hesper\Core\Exception\BaseException;
use Hesper\Core\Exception\IOException;

/**
 * Class FileInputStream
 * @package Hesper\Main\Util\IO
 */
final class FileInputStream extends InputStream {

	private $fd = null;

	private $mark = null;

	public function __construct($nameOrFd) {
		if (is_resource($nameOrFd)) {
			if (get_resource_type($nameOrFd) !== 'stream') {
				throw new IOException('not a file resource');
			}

			$this->fd = $nameOrFd;

		} else {
			try {
				$this->fd = fopen($nameOrFd, 'rb');
			} catch (BaseException $e) {
				throw new IOException($e->getMessage());
			}
		}
	}

	public function __destruct() {
		try {
			$this->close();
		} catch (BaseException $e) {
			// boo.
		}
	}

	/**
	 * @return FileInputStream
	 **/
	public static function create($nameOrFd) {
		return new self($nameOrFd);
	}

	public function isEof() {
		return feof($this->fd);
	}

	/**
	 * @return FileInputStream
	 **/
	public function mark() {
		$this->mark = $this->getOffset();

		return $this;
	}

	public function getOffset() {
		return ftell($this->fd);
	}

	public function markSupported() {
		return true;
	}

	/**
	 * @return FileInputStream
	 **/
	public function reset() {
		return $this->seek($this->mark);
	}

	/**
	 * @return FileInputStream
	 **/
	public function seek($offset) {
		if (fseek($this->fd, $offset) < 0) {
			throw new IOException('mark has been invalidated');
		}

		return $this;
	}

	/**
	 * @return FileInputStream
	 **/
	public function close() {
		if (!fclose($this->fd)) {
			throw new IOException('failed to close the file');
		}

		return $this;
	}

	public function read($length) {
		return $this->realRead($length);
	}

	public function readString($length = null) {
		return $this->realRead($length, true);
	}

	public function realRead($length, $string = false) {
		$result = $string ? ($length === null ? fgets($this->fd) : fgets($this->fd, $length)) : fread($this->fd, $length);

		if ($string && $result === false && feof($this->fd)) {
			$result = null;
		} // fgets returns false on eof

		if ($result === false) {
			throw new IOException('failed to read from file');
		}

		if ($result === '') {
			$result = null;
		} // eof

		return $result;
	}
}
