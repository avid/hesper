<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Aleksey S. Denisov
 */
namespace Hesper\Main\DAO\Uncacher;

use Hesper\Core\Base\Assert;
use Hesper\Core\Cache\Cache;
use Hesper\Core\Cache\SemaphorePool;

/**
 * Class UncacherSmartDaoWorkerLists
 * @package Hesper\Main\DAO\Uncacher
 */
class UncacherSmartDaoWorkerLists implements UncacherBase {

	private $classNameMap = [];

	/**
	 * @return UncacherSmartDaoWorkerLists
	 */
	public static function create($className, $indexKey, $intKey) {
		return new self($className, $indexKey, $intKey);
	}

	public function __construct($className, $indexKey, $intKey) {
		$this->classNameMap[$className] = [$indexKey, $intKey];
	}

	public function getClassNameMap() {
		return $this->classNameMap;
	}

	/**
	 * @param $uncacher UncacherSmartDaoWorkerLists same as self class
	 *
	 * @return UncacherBase (this)
	 */
	public function merge(UncacherBase $uncacher) {
		Assert::isInstance($uncacher, get_class($this));

		return $this->mergeSelf($uncacher);
	}

	public function uncache() {
		foreach ($this->classNameMap as $className => $classNameRow) {
			list ($indexKey, $intKey) = $classNameRow;
			$this->uncacheClassName($className, $indexKey, $intKey);
		}
	}

	protected function uncacheClassName($className, $indexKey, $intKey) {
		$cache = Cache::me();
		$pool = SemaphorePool::me();

		if ($pool->get($intKey)) {
			$indexList = $cache->mark($className)
			                   ->get($indexKey);
			$cache->mark($className)
			      ->delete($indexKey);

			if ($indexList) {
				foreach (array_keys($indexList) as $key) {
					$cache->mark($className)
					      ->delete($key);
				}
			}

			$pool->free($intKey);

			return true;
		}

		$cache->mark($className)
		      ->delete($indexKey);

		return false;
	}

	/**
	 * @param UncacherBaseDaoWorker $uncacher
	 *
	 * @return UncacherBaseDaoWorker
	 */
	private function mergeSelf(UncacherSmartDaoWorkerLists $uncacher) {
		foreach ($uncacher->getClassNameMap() as $className => $classNameRow) {
			if (!isset($this->classNameMap[$className])) {
				$this->classNameMap[$className] = $classNameRow;
			}
		}

		return $this;
	}
}
