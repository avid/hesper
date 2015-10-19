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
 * Sys-V shared memory cache.
 * @package Hesper\Core\Cache
 */
final class SharedMemory extends SelectivePeer {

	const INDEX_SEGMENT = 12345678;

	const DEFAULT_SEGMENT_SIZE = 4194304; // 128^3 * 2

	private $defaultSize = null;
	private $customSized = [];

	private static $attached = [];

	/**
	 * @return SharedMemory
	 **/
	public static function create($defaultSize = self::DEFAULT_SEGMENT_SIZE, $customSized = []) {
		return new self($defaultSize, $customSized);
	}

	/**
	 * @return SharedMemory
	 **/
	public function __construct($defaultSize = self::DEFAULT_SEGMENT_SIZE, $customSized = [] // 'className' => segmentSizeInBytes
	) {
		$this->defaultSize = $defaultSize;
		$this->customSized = $customSized;
	}

	public function __destruct() {
		foreach (self::$attached as $segment) {
			shm_detach($segment);
		}

		// sync classes
		$segment = shm_attach(self::INDEX_SEGMENT, self::DEFAULT_SEGMENT_SIZE, HESPER_IPC_PERMS);

		try {
			$index = shm_get_var($segment, 1);
		} catch (BaseException $e) {
			$index = [];
		}

		try {
			shm_put_var($segment, 1, array_unique(array_merge($index, array_keys(self::$attached))));
		} catch (BaseException $e) {/*_*/
		}

		try {
			shm_detach($segment);
		} catch (BaseException $e) {/*_*/
		}
	}

	public function increment($key, $value) {
		if (null !== ($current = $this->get($key))) {
			$this->set($key, $current += $value);

			return $current;
		}

		return null;
	}

	public function decrement($key, $value) {
		if (null !== ($current = $this->get($key))) {
			$this->set($key, $current -= $value);

			return $current;
		}

		return null;
	}

	public function get($key) {
		$segment = $this->getSegment();

		$key = $this->stringToInt($key);

		try {
			$stored = shm_get_var($segment, $key);

			if ($stored['expires'] <= time()) {
				$this->delete($key);

				return null;
			}

			return $this->restoreData($stored['value']);

		} catch (BaseException $e) {
			// not found there
			return null;
		}
	}

	public function delete($key) {
		try {
			return shm_remove_var($this->getSegment(), $this->stringToInt($key));
		} catch (BaseException $e) {
			return false;
		}
	}

	public function isAlive() {
		// any better idea how to detect shm-availability?
		return true;
	}

	/**
	 * @return SharedMemory
	 **/
	public function clean() {
		$segment = shm_attach(self::INDEX_SEGMENT);

		try {
			$index = shm_get_var($segment, 1);
		} catch (BaseException $e) {
			// nothing to clean
			return null;
		}

		foreach ($index as $key) {
			try {
				$sem = shm_attach($this->stringToInt($key));
				shm_remove($sem);
			} catch (BaseException $e) {
				// already removed, probably
			}
		}

		shm_remove($segment);

		return parent::clean();
	}

	public function append($key, $data) {
		$segment = $this->getSegment();

		$key = $this->stringToInt($key);

		try {
			$stored = shm_get_var($segment, $key);

			if ($stored['expires'] <= time()) {
				$this->delete($key);

				return false;
			}

			return $this->store('ignored', $key, $stored['value'] . $data, $stored['expires']);
		} catch (BaseException $e) {
			// not found there
			return false;
		}
	}

	protected function store($action, $key, $value, $expires = 0) {
		$segment = $this->getSegment();

		if ($expires < parent::TIME_SWITCH) {
			$expires += time();
		}

		try {
			shm_put_var($segment, $this->stringToInt($key), ['value' => $this->prepareData($value), 'expires' => $expires]);

			return true;

		} catch (BaseException $e) {
			// not enough memory
			return false;
		}
	}

	private function getSegment() {
		$class = $this->getClassName();

		if (!isset(self::$attached[$class])) {
			self::$attached[$class] = shm_attach($this->stringToInt($class), isset($this->customSized[$class]) ? $this->customSized[$class] : $this->defaultSize, HESPER_IPC_PERMS);
		}

		return self::$attached[$class];
	}

	private function stringToInt($string) {
		return hexdec(substr(md5($string), 0, 8));
	}
}
