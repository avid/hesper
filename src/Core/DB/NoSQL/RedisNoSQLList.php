<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Artem Naumenko
 */
namespace Hesper\Core\DB\NoSQL;

use Hesper\Core\Base\Listable;
use Hesper\Core\Exception\UnimplementedFeatureException;

final class RedisNoSQLList implements Listable {

	/** @var \Redis|null */
	private $redis    = null;
	private $key      = null;
	private $position = null;
	private $timeout  = null;

	public function __construct(\Redis $redis, $key, $timeout = null) {
		$this->redis = $redis;
		$this->key = $key;
		$this->timeout = $timeout;
	}

	/**
	 * @param mixed $value
	 *
	 * @return RedisNoSQLList
	 */
	public function append($value) {
		$this->redis->rpush($this->key, $value);

		if ($this->timeout) {
			$this->redis->setTimeout($this->key, $this->timeout);
		}

		return $this;
	}

	/**
	 * @param mixed $value
	 *
	 * @return RedisNoSQLList
	 */
	public function prepend($value) {
		$this->redis->lpush($this->key, $value);

		if ($this->timeout) {
			$this->redis->setTimeout($this->key, $this->timeout);
		}

		return $this;
	}

	/**
	 * @return RedisNoSQLList
	 */
	public function clear() {
		$this->redis->LTrim($this->key, -1, 0);

		return $this;
	}


	public function count() {
		return $this->redis->lsize($this->key);
	}

	public function pop() {
		return $this->redis->lpop($this->key);
	}

	public function range($start, $length = null) {
		$end = is_null($length) ? -1 : $start + $length;

		return $this->redis->lrange($this->key, $start, $end);
	}

	public function get($index) {
		return $this->redis->lget($this->key, $index);
	}

	public function set($index, $value) {
		$this->redis->lset($this->key, $index, $value);

		if ($this->timeout) {
			$this->redis->expire($this->key, $this->timeout);
		}

		return $this;
	}

	public function trim($start, $length = null) {
		$end = is_null($length) ? -1 : $start + $length - 1;

		$this->redis->ltrim($this->key, $start, $end);
	}

	//region Iterator
	public function current() {
		return $this->get($this->position);
	}

	public function key() {
		return $this->position;
	}

	public function next() {
		$this->position++;
	}

	public function rewind() {
		$this->position = 0;
	}

	public function valid() {
		return $this->offsetExists($this->position);
	}
	//endregion

	//region ArrayAccess

	public function offsetExists($offset) {
		return false !== $this->get($offset);
	}

	public function offsetGet($offset) {
		return $this->get($offset);
	}

	public function offsetSet($offset, $value) {
		return $this->set($offset, $value);
	}

	public function offsetUnset($offset) {
		throw new UnimplementedFeatureException();
	}

	public function seek($position) {
		$this->position = $position;
	}
	//endregion
}
