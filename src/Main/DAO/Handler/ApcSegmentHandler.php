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
 * Class ApcSegmentHandler
 * @see     http://pecl.php.net/package/APC
 * @package Hesper\Main\DAO\Handler
 */
final class ApcSegmentHandler extends OptimizerSegmentHandler {

	public function __construct($segmentId) {
		parent::__construct($segmentId);

		$this->locker = SemaphorePool::me();
	}

	public function drop() {
		return apc_delete($this->id);
	}

	protected function getMap() {
		$this->locker->get($this->id);

		if (!$map = apc_fetch($this->id)) {
			$map = [];
		}

		return $map;
	}

	protected function storeMap(array $map) {
		$result = apc_store($this->id, $map, Cache::EXPIRES_FOREVER);

		$this->locker->free($this->id);

		return $result;
	}
}
