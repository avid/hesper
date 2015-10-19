<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Core\Cache;

use Hesper\Core\Base\Instantiatable;
use Hesper\Core\Base\Singleton;

/**
 * Memcached based locking.
 * No synchronization between local pool and memcached daemons!
 * @package Hesper\Core\Cache
 */
final class MemcachedLocker extends BaseLocker implements Instantiatable {

	const VALUE = 0x1;

	private $memcachedClient = null;

	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function setMemcachedClient(CachePeer $memcachedPeer) {
		$this->memcachedClient = $memcachedPeer;

		return $this;
	}

	public function get($key) {
		return $this->memcachedClient->add($key, self::VALUE, 2 * Cache::EXPIRES_MINIMUM);
	}

	public function free($key) {
		return $this->memcachedClient->delete($key);
	}

	public function drop($key) {
		return $this->free($key);
	}
}
