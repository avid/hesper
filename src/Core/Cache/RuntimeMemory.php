<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Cache;

/**
 * Default process RAM cache.
 * @package Hesper\Core\Cache
 */
final class RuntimeMemory extends CachePeer {

	private $cache = [];

	/**
	 * @return RuntimeMemory
	 **/
	public static function create() {
		return new self;
	}

	public function isAlive() {
		return true;
	}

	public function increment($key, $value) {
		if (isset($this->cache[$key])) {
			return $this->cache[$key] += $value;
		}

		return null;
	}

	public function decrement($key, $value) {
		if (isset($this->cache[$key])) {
			return $this->cache[$key] -= $value;
		}

		return null;
	}

	public function get($key) {
		if (isset($this->cache[$key])) {
			return $this->cache[$key];
		}

		return null;
	}

	public function delete($key) {
		if (isset($this->cache[$key])) {
			unset($this->cache[$key]);

			return true;
		}

		return false;
	}

	/**
	 * @return RuntimeMemory
	 **/
	public function clean() {
		$this->cache = [];

		return parent::clean();
	}

	public function append($key, $data) {
		if (isset($this->cache[$key])) {
			$this->cache[$key] .= $data;

			return true;
		}

		return false;
	}

	protected function store($action, $key, $value, $expires = 0) {
		if ($action == 'add' && isset($this->cache[$key])) {
			return true;
		} elseif ($action == 'replace' && !isset($this->cache[$key])) {
			return false;
		}

		if (is_object($value)) {
			$this->cache[$key] = clone $value;
		} else {
			$this->cache[$key] = $value;
		}

		return true;
	}
}
