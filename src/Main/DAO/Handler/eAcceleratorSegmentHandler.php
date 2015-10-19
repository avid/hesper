<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\DAO\Handler;

use Hesper\Core\Base\Singleton;
use Hesper\Core\Cache\Cache;
use Hesper\Core\Cache\eAcceleratorLocker;

/**
 * Class eAcceleratorSegmentHandler
 * @see     http://eaccelerator.net/
 * @package Hesper\Main\DAO\Handler
 */
final class eAcceleratorSegmentHandler extends OptimizerSegmentHandler {

	public function __construct($segmentId) {
		parent::__construct($segmentId);

		$this->locker = Singleton::getInstance(eAcceleratorLocker::class);
	}

	public function drop() {
		return eaccelerator_rm($this->id);
	}

	protected function getMap() {
		$this->locker->get($this->id);

		if (!$map = eaccelerator_get($this->id)) {
			$map = [];
		}

		return $map;
	}

	protected function storeMap(array $map) {
		$result = eaccelerator_put($this->id, $map, Cache::EXPIRES_FOREVER);

		$this->locker->free($this->id);

		return $result;
	}
}
