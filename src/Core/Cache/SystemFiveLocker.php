<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Cache;

use Hesper\Core\Exception\BaseException;

/**
 * System-V semaphores based locking.
 * @package Hesper\Core\Cache
 */
final class SystemFiveLocker extends BaseLocker {

	public function get($key) {
		try {
			if (!isset($this->pool[$key])) {
				$this->pool[$key] = sem_get($key, 1, HESPER_IPC_PERMS, false);
			}

			return sem_acquire($this->pool[$key]);
		} catch (BaseException $e) {
			return null;
		}
	}

	public function free($key) {
		if (isset($this->pool[$key])) {
			try {
				return sem_release($this->pool[$key]);
			} catch (BaseException $e) {
				// acquired by another process
				return false;
			}
		}

		return null;
	}

	public function drop($key) {
		if (isset($this->pool[$key])) {
			try {
				return sem_remove($this->pool[$key]);
			} catch (BaseException $e) {
				unset($this->pool[$key]); // already race-removed
				return false;
			}
		}

		return null;
	}
}
