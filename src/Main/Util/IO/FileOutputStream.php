<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Util\IO;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\BaseException;
use Hesper\Core\Exception\IOException;

/**
 * Class FileOutputStream
 * @package Hesper\Main\Util\Logging
 */
final class FileOutputStream extends OutputStream {

	private $fd = null;

	public function __construct($nameOrFd, $append = false) {
		if (is_resource($nameOrFd)) {
			if (get_resource_type($nameOrFd) !== 'stream') {
				throw new IOException('not a file resource');
			}

			$this->fd = $nameOrFd;

		} else {
			try {
				$this->fd = fopen($nameOrFd, ($append ? 'a' : 'w') . 'b');

				Assert::isNotFalse($this->fd, "File {$nameOrFd} must be exist");
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
	 * @return FileOutputStream
	 **/
	public static function create($nameOrFd, $append = false) {
		return new self($nameOrFd, $append);
	}

	/**
	 * @return FileOutputStream
	 **/
	public function write($buffer) {
		if (!$this->fd || $buffer === null) {
			return $this;
		}

		try {
			$written = fwrite($this->fd, $buffer);
		} catch (BaseException $e) {
			throw new IOException($e->getMessage());
		}

		if (!$written || $written < strlen($buffer)) {
			throw new IOException('disk full and/or buffer too large?');
		}

		return $this;
	}

	/**
	 * @return FileOutputStream
	 **/
	public function close() {
		fclose($this->fd);

		$this->fd = null;

		return $this;
	}
}
