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

/**
 * Class UncacherCacheDaoWorkerLists
 * @package Hesper\Main\DAO\Uncacher
 */
class UncacherCacheDaoWorkerLists implements UncacherBase {

	private $classNameList = [];

	/**
	 * @return UncacherBaseDaoWorker
	 */
	public static function create($className) {
		return new self($className);
	}

	public function __construct($className) {
		$this->classNameList[$className] = $className;
	}

	public function getClassNameList() {
		return $this->classNameList;
	}

	/**
	 * @param $uncacher UncacherCacheDaoWorkerLists same as self class
	 *
	 * @return UncacherBase (this)
	 */
	public function merge(UncacherBase $uncacher) {
		Assert::isInstance($uncacher, get_class($this));

		return $this->mergeSelf($uncacher);
	}

	public function uncache() {
		foreach ($this->classNameList as $className) {
			$this->uncacheClassName($className);
		}
	}

	private function uncacheClassName($className) {
		if (!Cache::me()
		          ->mark($className)
		          ->increment($className, 1)
		) {
			Cache::me()
			     ->mark($className)
			     ->delete($className);
		}
	}

	/**
	 * @param UncacherCacheDaoWorkerLists $uncacher
	 *
	 * @return UncacherCacheDaoWorkerLists
	 */
	private function mergeSelf(UncacherCacheDaoWorkerLists $uncacher) {
		foreach ($uncacher->getClassNameList() as $className) {
			if (!isset($this->classNameList[$className])) {
				$this->classNameList[$className] = $className;
			}
		}

		return $this;
	}
}
