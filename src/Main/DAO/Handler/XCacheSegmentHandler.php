<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\DAO\Handler;

use Hesper\Core\Cache\SemaphorePool;

/**
 * Class XCacheSegmentHandler
 * @see     http://trac.lighttpd.net/xcache/
 * @package Hesper\Main\DAO\Handler
 */
final class XCacheSegmentHandler extends OptimizerSegmentHandler {

	public function __construct($segmentId) {
		parent::__construct($segmentId);

		$this->locker = SemaphorePool::me();
	}

	public function drop() {
		return xcache_unset($this->id);
	}

	public function ping($key) {
		if (xcache_isset($this->id)) {
			return parent::ping($key);
		} else {
			return false;
		}
	}

	protected function getMap() {
		$this->locker->get($this->id);

		if (!$map = xcache_get($this->id)) {
			$map = [];
		}

		return $map;
	}

	protected function storeMap(array $map) {
		$result = xcache_set($this->id, $map);

		$this->locker->free($this->id);

		return $result;
	}
}
