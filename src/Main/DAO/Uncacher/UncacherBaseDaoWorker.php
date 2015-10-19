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
use Hesper\Main\Util\ArrayUtils;

/**
 * Class UncacherBaseDaoWorker
 * @package Hesper\Main\DAO\Uncacher
 */
class UncacherBaseDaoWorker implements UncacherBase {

	private $classNameMap = [];

	/**
	 * @return UncacherBaseDaoWorker
	 */
	public static function create($className, $idKey) {
		return new self($className, $idKey);
	}

	public function __construct($className, $idKey) {
		$this->classNameMap[$className] = [$idKey];
	}

	public function getClassNameMap() {
		return $this->classNameMap;
	}

	/**
	 * @param $uncacher UncacherNullDaoWorker same as self class
	 *
	 * @return UncacherBase (this)
	 */
	public function merge(UncacherBase $uncacher) {
		Assert::isInstance($uncacher, get_class($this));

		return $this->mergeSelf($uncacher);
	}

	public function uncache() {
		foreach ($this->classNameMap as $className => $idKeys) {
			foreach ($idKeys as $key) {
				$this->uncacheClassName($className, $idKeys);
			}
		}
	}

	protected function uncacheClassName($className, $idKeys) {
		foreach ($idKeys as $key) {
			Cache::me()
			     ->mark($className)
			     ->delete($key);
		}
	}

	/**
	 * @param UncacherBaseDaoWorker $uncacher
	 *
	 * @return UncacherBaseDaoWorker
	 */
	private function mergeSelf(UncacherBaseDaoWorker $uncacher) {
		foreach ($uncacher->getClassNameMap() as $className => $idKeys) {
			if (isset($this->classNameMap[$className])) {
				$this->classNameMap[$className] = ArrayUtils::mergeUnique($this->classNameMap[$className], $idKeys);
			} else {
				$this->classNameMap[$className] = $idKeys;
			}
		}

		return $this;
	}
}
