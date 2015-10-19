<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Main\DAO\Worker;

use Hesper\Core\Cache\Cache;
use Hesper\Core\Cache\WatermarkedPeer;

/**
 * Cache custom scoped data
 * @see     CommonDaoWorker for manual-caching one.
 * @see     SmartDaoWorker for transparent one.
 * @package Hesper\Main\DAO\Worker
 */
final class CustomDataScopedWorker extends CacheDaoWorker {

	public function __construct($dao) {
		$this->dao = $dao;

		$this->className = $dao->getObjectName();

		if (($cache = Cache::me()) instanceof WatermarkedPeer) {
			$this->watermark = $cache->mark($this->className)
			                         ->getActualWatermark();
		}
	}

	public function cacheData($key, $data, $expires = Cache::EXPIRES_FOREVER) {
		Cache::me()
		     ->mark($this->className)
		     ->add($this->makeDataKey($key, self::SUFFIX_QUERY), $data, $expires);

		return $data;
	}

	public function getCachedData($key) {
		return Cache::me()
		            ->mark($this->className)
		            ->get($this->makeDataKey($key, self::SUFFIX_QUERY));
	}

	private function makeDataKey($key, $suffix) {
		return $this->className . $suffix . $key . $this->watermark . $this->getLayerId();
	}
}
