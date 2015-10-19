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
use Hesper\Core\OSQL\SelectQuery;
use Hesper\Main\DAO\Uncacher\UncacherCacheDaoWorkerLists;

/**
 * Transparent and scalable DAO worker, Jedi's best choice.
 * @see     CommonDaoWorker for manual-caching one.
 * @see     SmartDaoWorker for locking-based worker.
 * @see     VoodooDaoWorker for greedy and unscalable one.
 * @package Hesper\Main\DAO\Worker
 */
class CacheDaoWorker extends TransparentDaoWorker {

	const MAX_RANDOM_ID = 134217728;

	/// cachers
	//@{
	protected function cacheByQuery(SelectQuery $query, /* Identifiable */
	                                $object, $expires = Cache::EXPIRES_FOREVER) {
		Cache::me()
		     ->mark($this->className)
		     ->add($this->makeQueryKey($query, self::SUFFIX_QUERY), $object, $expires);

		return $object;
	}

	protected function cacheListByQuery(SelectQuery $query, /* array || Cache::NOT_FOUND */
	                                    $array) {
		if ($array !== Cache::NOT_FOUND) {
			Assert::isArray($array);
			Assert::isTrue(current($array) instanceof Identifiable);
		}

		Cache::me()
		     ->mark($this->className)
		     ->add($this->makeQueryKey($query, self::SUFFIX_LIST), $array, Cache::EXPIRES_FOREVER);

		return $array;
	}
	//@}

	/// uncachers
	//@{
	public function uncacheLists() {
		return $this->registerUncacher(UncacherCacheDaoWorkerLists::create($this->className));
	}
	//@}

	/// internal helpers
	//@{
	protected function gentlyGetByKey($key) {
		return Cache::me()
		            ->mark($this->className)
		            ->get($key);
	}

	protected function getLayerId() {
		if (!$result = Cache::me()
		                    ->mark($this->className)
		                    ->get($this->className)
		) {
			$result = mt_rand(1, self::MAX_RANDOM_ID);

			Cache::me()
			     ->mark($this->className)
			     ->set($this->className, $result, Cache::EXPIRES_FOREVER);
		}

		return '@' . $result;
	}

	protected function makeQueryKey(SelectQuery $query, $suffix) {
		return parent::makeQueryKey($query, $suffix) . $this->getLayerId();
	}
	//@}
}
