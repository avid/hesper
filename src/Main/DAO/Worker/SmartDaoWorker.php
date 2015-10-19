<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\DAO\Worker;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Identifiable;
use Hesper\Core\Cache\Cache;
use Hesper\Core\Cache\SemaphorePool;
use Hesper\Core\OSQL\SelectQuery;
use Hesper\Main\DAO\GenericDAO;
use Hesper\Main\DAO\Uncacher\UncacherSmartDaoWorkerLists;

/**
 * Transparent caching DAO worker.
 * @see     CommonDaoWorker for manual-caching one.
 * @see     VoodooDaoWorker for greedy though non-blocking brother.
 * @package Hesper\Main\DAO\Worker
 */
final class SmartDaoWorker extends TransparentDaoWorker {

	private $indexKey = null;

	public function __construct(GenericDAO $dao) {
		parent::__construct($dao);

		$this->indexKey = $this->watermark . $this->className . self::SUFFIX_INDEX;
	}

	/// cachers
	//@{
	protected function cacheByQuery(SelectQuery $query, /* Identifiable */
	                                $object, $expires = Cache::EXPIRES_FOREVER) {
		$queryId = $query->getId();

		$semKey = $this->keyToInt($this->indexKey);

		$key = $this->makeQueryKey($query, self::SUFFIX_QUERY);

		$pool = SemaphorePool::me();

		if ($pool->get($semKey)) {
			$this->syncMap($key);

			Cache::me()
			     ->mark($this->className)
			     ->add($key, $object, $expires);

			$pool->free($semKey);
		}

		return $object;
	}

	protected function cacheListByQuery(SelectQuery $query, /* array || Cache::NOT_FOUND */
	                                    $array) {
		if ($array !== Cache::NOT_FOUND) {
			Assert::isArray($array);
			Assert::isTrue(current($array) instanceof Identifiable);
		}

		$cache = Cache::me();

		$listKey = $this->makeQueryKey($query, self::SUFFIX_LIST);

		$semKey = $this->keyToInt($this->indexKey);

		$pool = SemaphorePool::me();

		if ($pool->get($semKey)) {

			$this->syncMap($listKey);

			$cache->mark($this->className)
			      ->add($listKey, $array, Cache::EXPIRES_FOREVER);

			if ($array !== Cache::NOT_FOUND) {
				foreach ($array as $object) {
					$this->cacheById($object);
				}
			}

			$pool->free($semKey);
		}

		return $array;
	}
	//@}

	/// uncachers
	//@{
	public function uncacheLists() {
		$intKey = $this->keyToInt($this->indexKey);

		return $this->registerUncacher(UncacherSmartDaoWorkerLists::create($this->className, $this->indexKey, $intKey));
	}
	//@}

	/// internal helpers
	//@{
	protected function gentlyGetByKey($key) {
		if ($object = Cache::me()
		                   ->mark($this->className)
		                   ->get($key)
		) {
			if ($this->checkMap($key)) {
				return $object;
			} else {
				Cache::me()
				     ->mark($this->className)
				     ->delete($key);
			}
		}

		return null;
	}

	private function syncMap($objectKey) {
		$cache = Cache::me();

		$mapExists = true;
		if (!$map = $cache->mark($this->className)
		                  ->get($this->indexKey)
		) {
			$map = [];
			$mapExists = false;
		}

		$map[$objectKey] = true;

		if ($mapExists) {
			$cache->mark($this->className)
			      ->replace($this->indexKey, $map, Cache::EXPIRES_FOREVER);
		} else {
			$cache->mark($this->className)
			      ->set($this->indexKey, $map, Cache::EXPIRES_FOREVER);
		}

		return true;
	}

	private function checkMap($objectKey) {
		$pool = SemaphorePool::me();

		$semKey = $this->keyToInt($this->indexKey);

		if (!$pool->get($semKey)) {
			return false;
		}

		if (!$map = Cache::me()
		                 ->mark($this->className)
		                 ->get($this->indexKey)
		) {
			$pool->free($semKey);

			return false;
		}

		if (!isset($map[$objectKey])) {
			$pool->free($semKey);

			return false;
		}

		$pool->free($semKey);

		return true;
	}
	//@}
}
