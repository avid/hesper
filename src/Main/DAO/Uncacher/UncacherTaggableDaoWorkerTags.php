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
use Hesper\Main\DAO\StorableDAO;
use Hesper\Main\DAO\Worker\TaggableDaoWorker;
use Hesper\Main\Util\ArrayUtils;
use Hesper\Main\Util\ClassUtils;

/**
 * Class UncacherTaggableDaoWorkerTags
 * @package Hesper\Main\DAO\Uncacher
 */
class UncacherTaggableDaoWorkerTags implements UncacherBase {

	private $classNameMap = [];

	/**
	 * @return UncacherTaggableDaoWorkerTags
	 */
	public static function create($className, array $tags = []) {
		return new self($className, $tags);
	}

	public function __construct($className, array $tags = []) {
		$this->classNameMap[$className] = $tags;
	}

	/**
	 * @return array
	 */
	public function getClassNameMap() {
		return $this->classNameMap;
	}

	/**
	 * @param $uncacher UncacherTaggableDaoWorkerTags same as self class
	 *
	 * @return UncacherBase (this)
	 */
	public function merge(UncacherBase $uncacher) {
		Assert::isInstance($uncacher, self::class);

		return $this->mergeSelf($uncacher);
	}

	public function uncache() {
		foreach ($this->classNameMap as $className => $tags) {
			$dao = ClassUtils::callStaticMethod("$className::dao");
			/* @var $dao StorableDAO */
			$worker = Cache::worker($dao);
			Assert::isInstance($worker, TaggableDaoWorker::class);

			$worker->expireTags($tags);
		}
	}

	private function mergeSelf(UncacherTaggableDaoWorkerTags $uncacher) {
		foreach ($uncacher->getClassNameMap() as $className => $tags) {
			if (!isset($this->classNameMap[$className])) {
				$this->classNameMap[$className] = $tags;
			} else {
				//merging idkeys
				$this->classNameMap[$className] = ArrayUtils::mergeUnique($this->classNameMap[$className], $tags);
			}
		}

		return $this;
	}
}
