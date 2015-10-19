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
 * File based locker.
 * @package Hesper\Core\Cache
 */
final class FileLocker extends BaseLocker {

	private $directory = null;

	public function __construct($directory = 'file-locking/') {
		$this->directory = HESPER_TEMP_PATH . $directory;

		if (!is_writable($this->directory)) {
			if (!mkdir($this->directory, 0700, true)) {
				throw new WrongArgumentException("can not write to '{$directory}'");
			}
		}
	}

	public function get($key) {
		$this->pool[$key] = fopen($this->directory . $key, 'w+');

		return flock($this->pool[$key], LOCK_EX);
	}

	public function free($key) {
		return flock($this->pool[$key], LOCK_UN);
	}

	public function drop($key) {
		try {
			fclose($this->pool[$key]);

			return unlink($this->directory . $key);
		} catch (BaseException $e) {
			unset($this->pool[$key]); // already race-removed
			return false;
		}
	}
}
