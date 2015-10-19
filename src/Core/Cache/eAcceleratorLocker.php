<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Cache;

/**
 * Class eAcceleratorLocker
 * @see     http://eaccelerator.net/
 * @package Hesper\Core\Cache
 */
final class eAcceleratorLocker extends BaseLocker {

	public function get($key) {
		return eaccelerator_lock($key);
	}

	public function free($key) {
		return eaccelerator_unlock($key);
	}

	public function drop($key) {
		return $this->free($key);
	}

	public function clean() {
		// will be cleaned out upon script's shutdown
		return true;
	}
}
