<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\DAO\Worker;

use Hesper\Core\Base\Identifiable;
use Hesper\Core\Cache\Cache;
use Hesper\Core\Exception\CachedObjectNotFoundException;
use Hesper\Core\Exception\ObjectNotFoundException;
use Hesper\Core\Logic\LogicalObject;
use Hesper\Core\OSQL\SelectQuery;

/**
 * Basis for transparent DAO workers.
 * @see     VoodooDaoWorker for obscure and greedy worker.
 * @see     SmartDaoWorker for less obscure locking-based worker.
 * @package Hesper\Main\DAO\Worker
 */
abstract class TransparentDaoWorker extends CommonDaoWorker {

	abstract protected function gentlyGetByKey($key);

	/// single object getters
	//@{
	public function getById($id) {
		try {
			return parent::getById($id, Cache::EXPIRES_FOREVER);
		} catch (CachedObjectNotFoundException $e) {
			throw $e;
		} catch (ObjectNotFoundException $e) {
			$this->cacheNullById($id);
			throw $e;
		}
	}

	public function getByLogic(LogicalObject $logic) {
		return parent::getByLogic($logic, Cache::EXPIRES_FOREVER);
	}

	public function getByQuery(SelectQuery $query) {
		try {
			return parent::getByQuery($query, Cache::EXPIRES_FOREVER);
		} catch (CachedObjectNotFoundException $e) {
			throw $e;
		} catch (ObjectNotFoundException $e) {
			$this->cacheByQuery($query, Cache::NOT_FOUND);
			throw $e;
		}
	}

	public function getCustom(SelectQuery $query) {
		try {
			return parent::getCustom($query, Cache::EXPIRES_FOREVER);
		} catch (CachedObjectNotFoundException $e) {
			throw $e;
		} catch (ObjectNotFoundException $e) {
			$this->cacheByQuery($query, Cache::NOT_FOUND);
			throw $e;
		}
	}
	//@}

	/// object's list getters
	//@{
	public function getListByIds(array $ids) {
		$list = [];
		$toFetch = [];
		$prefixed = [];

		$proto = $this->dao->getProtoClass();

		$proto->beginPrefetch();

		// dupes, if any, will be resolved later @ ArrayUtils::regularizeList
		$ids = array_unique($ids);

		foreach ($ids as $id) {
			$prefixed[$id] = $this->makeIdKey($id);
		}

		if ($cachedList = Cache::me()
		                       ->mark($this->className)
		                       ->getList($prefixed)
		) {
			foreach ($cachedList as $cached) {
				if ($cached && ($cached !== Cache::NOT_FOUND)) {
					$list[] = $this->dao->completeObject($cached);

					unset($prefixed[$cached->getId()]);
				}
			}
		}

		$toFetch += array_keys($prefixed);

		if ($toFetch) {
			$remainList = [];

			foreach ($toFetch as $id) {
				try {
					$remainList[] = $this->getById($id);
				} catch (ObjectNotFoundException $e) {/*_*/
				}
			}

			$list = array_merge($list, $remainList);
		}

		$proto->endPrefetch($list);

		return $list;
	}

	public function getListByQuery(SelectQuery $query) {
		$list = $this->getCachedList($query);

		if ($list) {
			if ($list === Cache::NOT_FOUND) {
				throw new ObjectNotFoundException();
			} else {
				return $list;
			}
		} else {
			if ($list = $this->fetchList($query)) {
				return $this->cacheListByQuery($query, $list);
			} else {
				$this->cacheListByQuery($query, Cache::NOT_FOUND);
				throw new ObjectNotFoundException();
			}
		}
	}

	public function getListByLogic(LogicalObject $logic) {
		return parent::getListByLogic($logic, Cache::EXPIRES_FOREVER);
	}

	public function getPlainList() {
		return parent::getPlainList(Cache::EXPIRES_FOREVER);
	}
	//@}

	/// custom list getters
	//@{
	public function getCustomList(SelectQuery $query) {
		try {
			return parent::getCustomList($query, Cache::EXPIRES_FOREVER);
		} catch (CachedObjectNotFoundException $e) {
			throw $e;
		} catch (ObjectNotFoundException $e) {
			$this->cacheByQuery($query, Cache::NOT_FOUND);
			throw $e;
		}
	}

	public function getCustomRowList(SelectQuery $query) {
		try {
			return parent::getCustomRowList($query, Cache::EXPIRES_FOREVER);
		} catch (CachedObjectNotFoundException $e) {
			throw $e;
		} catch (ObjectNotFoundException $e) {
			$this->cacheByQuery($query, Cache::NOT_FOUND);
			throw $e;
		}
	}
	//@}

	/// query result getters
	//@{
	public function getQueryResult(SelectQuery $query) {
		return parent::getQueryResult($query, Cache::EXPIRES_FOREVER);
	}
	//@}

	/// cachers
	//@{
	protected function cacheById(Identifiable $object, $expires = Cache::EXPIRES_FOREVER) {
		Cache::me()
		     ->mark($this->className)
		     ->add($this->makeIdKey($object->getId()), $object, $expires);

		return $object;
	}
	//@}

	/// internal helpers
	//@{
	protected function getCachedByQuery(SelectQuery $query) {
		return $this->gentlyGetByKey($this->makeQueryKey($query, self::SUFFIX_QUERY));
	}

	protected function getCachedList(SelectQuery $query) {
		return $this->gentlyGetByKey($this->makeQueryKey($query, self::SUFFIX_LIST));
	}

	protected function cacheNullById($id) {
		return Cache::me()
		            ->mark($this->className)
		            ->add($this->makeIdKey($id), Cache::NOT_FOUND, Cache::EXPIRES_FOREVER);
	}

	protected function keyToInt($key) {
		// 7 == strlen(dechex(x86 PHP_INT_MAX)) - 1
		return hexdec(substr(md5($key), 0, 7)) + strlen($key);
	}
	//@}
}
