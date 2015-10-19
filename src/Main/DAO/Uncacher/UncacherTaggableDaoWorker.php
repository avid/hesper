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
 * Class UncacherTaggableDaoWorker
 * @package Hesper\Main\DAO\Uncacher
 */
class UncacherTaggableDaoWorker implements UncacherBase {

	private $classNameMap = [];

	/**
	 * @return UncacherTaggableDaoWorker
	 */
	public static function create($className, $idKey, array $tags = []) {
		return new self($className, $idKey, $tags);
	}

	public function __construct($className, $idKey, array $tags = []) {
		$idKeyList = $idKey ? [$idKey] : [];
		$this->classNameMap[$className] = [$idKeyList, $tags];
	}

	/**
	 * @return array
	 */
	public function getClassNameMap() {
		return $this->classNameMap;
	}

	/**
	 * @param $uncacher UncacherNullDaoWorker same as self class
	 *
	 * @return UncacherBase (this)
	 */
	public function merge(UncacherBase $uncacher) {
		Assert::isInstance($uncacher, self::class);

		return $this->mergeSelf($uncacher);
	}

	public function uncache() {
		foreach ($this->classNameMap as $className => $uncaches) {
			list($idKeys, $tags) = $uncaches;
			$dao = ClassUtils::callStaticMethod("$className::dao");
			/* @var $dao StorableDAO */
			$worker = Cache::worker($dao);
			Assert::isInstance($worker, TaggableDaoWorker::class);

			$worker->expireTags($tags);

			foreach ($idKeys as $key) {
				Cache::me()
				     ->mark($className)
				     ->delete($key);
			}

			$dao->uncacheLists();
		}
	}

	private function mergeSelf(UncacherTaggableDaoWorker $uncacher) {
		foreach ($uncacher->getClassNameMap() as $className => $uncaches) {
			if (!isset($this->classNameMap[$className])) {
				$this->classNameMap[$className] = $uncaches;
			} else {
				//merging idkeys
				$this->classNameMap[$className][0] = ArrayUtils::mergeUnique($this->classNameMap[$className][0], $uncaches[0]);
				//merging tags
				$this->classNameMap[$className][1] = ArrayUtils::mergeUnique($this->classNameMap[$className][1], $uncaches[1]);
			}
		}

		return $this;
	}
}
