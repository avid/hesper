<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Cache;

use Hesper\Core\Exception\BaseException;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Directories based locker.
 * @package Hesper\Core\Cache
 */
final class DirectoryLocker extends BaseLocker {

	private $directory = null;

	protected function __construct($directory = 'dir-locking/') {
		$this->directory = HESPER_TEMP_PATH . $directory;

		if (!is_writable($this->directory)) {
			if (!mkdir($this->directory, 0700, true)) {
				throw new WrongArgumentException("can not write to '{$directory}'");
			}
		}
	}

	public function get($key) {
		$mseconds = 0;

		while ($mseconds < 10000) {
			try {
				mkdir($this->directory . $key, 0700, false);

				return $this->pool[$key] = true;
			} catch (BaseException $e) {
				// still exist
				unset($e);
				$mseconds += 200;
				usleep(200);
			}
		}

		return false;
	}

	public function free($key) {
		try {
			return rmdir($this->directory . $key);
		} catch (BaseException $e) {
			return false;
		}
	}

	public function drop($key) {
		return $this->free($key);
	}
}
