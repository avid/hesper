<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Artem Naumenko
 */
namespace Hesper\Core\Cache;

use Hesper\Core\Exception\UnsupportedMethodException;
use RuntimeException;

/**
 * Class SequentialCache
 * @package Hesper\Core\Cache
 */
final class SequentialCache extends CachePeer {

	/**
	 * List of all peers, including master
	 * @var array of CachePeer
	 */
	private $list = [];

	/**
	 * List of slaves only
	 * @var array of CachePeer
	 */
	private $slaves = [];

	/**
	 * @var CachePeer
	 */
	private $master = null;

	/**
	 * @param CachePeer $master
	 * @param array     $slaves or CachePeer
	 *
	 * @return SequentialCache
	 */
	public static function create(CachePeer $master, array $slaves = []) {
		return new self($master, $slaves);
	}

	/**
	 * @param CachePeer $master
	 * @param array     $slaves or CachePeer
	 */
	public function __construct(CachePeer $master, array $slaves = []) {
		$this->setMaster($master);

		foreach ($slaves as $cache) {
			$this->addPeer($cache);
		}
	}

	/**
	 * @param CachePeer $master
	 *
	 * @return SequentialCache
	 */
	public function setMaster(CachePeer $master) {
		$this->master = $master;
		$this->list = $this->slaves;
		array_unshift($this->list, $this->master);

		return $this;
	}

	/**
	 * @param CachePeer $master
	 *
	 * @return SequentialCache
	 */
	public function addPeer(CachePeer $peer) {
		$this->list[] = $peer;
		$this->slaves[] = $peer;

		return $this;
	}

	public function get($key) {
		foreach ($this->list as $val) {
			/* @var $val CachePeer */
			$result = $val->get($key);

			if (!empty($result) || $val->isAlive()) {
				return $result;
			}
		}

		throw new RuntimeException('All peers are dead');
	}

	public function append($key, $data) {
		return $this->foreachItem(__METHOD__, func_get_args());
	}

	public function decrement($key, $value) {
		throw new UnsupportedMethodException('decrement is not supported');
	}

	public function delete($key) {
		return $this->foreachItem(__METHOD__, func_get_args());
	}

	public function increment($key, $value) {
		throw new UnsupportedMethodException('increment is not supported');
	}

	protected function store($action, $key, $value, $expires = Cache::EXPIRES_MEDIUM) {
		return $this->foreachItem(__METHOD__, func_get_args());
	}

	private function foreachItem($method, array $args) {
		$result = true;

		foreach ($this->list as $peer) {
			/* @var $peer CachePeer */
			$result = call_user_func_array([$peer, $method], $args) && $result;
		}

		return $result;
	}
}
