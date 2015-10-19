<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov, Anton E. Lebedevich
 */
namespace Hesper\Main\DAO\Worker;

use Hesper\Core\Base\Identifiable;
use Hesper\Core\Cache\Cache;
use Hesper\Core\DB\DBPool;
use Hesper\Core\Exception\CachedObjectNotFoundException;
use Hesper\Core\Exception\ObjectNotFoundException;
use Hesper\Core\Exception\UnimplementedFeatureException;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Logic\Expression;
use Hesper\Core\Logic\LogicalObject;
use Hesper\Core\OSQL\DBField;
use Hesper\Core\OSQL\QueryResult;
use Hesper\Core\OSQL\SelectQuery;
use Hesper\Core\OSQL\SQLFunction;
use Hesper\Main\DAO\Uncacher\UncacherBase;
use Hesper\Main\DAO\Uncacher\UncacherCommonDaoWorker;

/**
 * Tunable (aka manual) caching DAO worker.
 * @see     SmartDaoWorker for auto-caching one.
 * @package Hesper\Main\DAO\Worker
 */
class CommonDaoWorker extends BaseDaoWorker {

	/// single object getters
	//@{
	public function getById($id, $expires = Cache::EXPIRES_MEDIUM) {
		if (($expires !== Cache::DO_NOT_CACHE) && ($object = $this->getCachedById($id))) {
			if ($object === Cache::NOT_FOUND) {
				throw new CachedObjectNotFoundException("there is no such object for '" . $this->dao->getObjectName() . "' with id=" . $id);
			}

			return $this->dao->completeObject($object);
		} else {
			$query = $this->dao->makeSelectHead()->andWhere(Expression::eq(DBField::create($this->dao->getIdName(), $this->dao->getTable()), $id));

			if ($expires === Cache::DO_NOT_CACHE) {
				$object = $this->fetchObject($query);
			} else {
				$object = $this->cachedFetchObject($query, $expires, true);
			}

			if ($object) {
				return $object;
			} else {
				throw new ObjectNotFoundException("there is no such object for '" . $this->dao->getObjectName() . "' with query == " . $query->toDialectString(DBPool::me()->getByDao($this->dao)->getDialect()));
			}
		}
	}

	public function getByLogic(LogicalObject $logic, $expires = Cache::DO_NOT_CACHE) {
		return $this->getByQuery($this->dao->makeSelectHead()->andWhere($logic), $expires);
	}

	public function getByQuery(SelectQuery $query, $expires = Cache::DO_NOT_CACHE) {
		if (($expires !== Cache::DO_NOT_CACHE) && ($object = $this->getCachedByQuery($query))) {
			if ($object === Cache::NOT_FOUND) {
				throw new CachedObjectNotFoundException();
			}

			return $this->dao->completeObject($object);
		} else {
			if ($expires === Cache::DO_NOT_CACHE) {
				$object = $this->fetchObject($query);
			} else {
				$object = $this->cachedFetchObject($query, $expires, false);
			}

			if ($object) {
				return $object;
			} else {
				throw new ObjectNotFoundException("there is no such object for '" . $this->dao->getObjectName() . (defined('__LOCAL_DEBUG__') ? "' with query == " . $query->toDialectString(DBPool::me()->getByDao($this->dao)->getDialect()) : null));
			}
		}
	}

	public function getCustom(SelectQuery $query, $expires = Cache::DO_NOT_CACHE) {
		if ($query->getLimit() > 1) {
			throw new WrongArgumentException('can not handle non-single row queries');
		}

		$db = DBPool::getByDao($this->dao);

		if (($expires !== Cache::DO_NOT_CACHE) && ($object = $this->getCachedByQuery($query))) {
			if ($object === Cache::NOT_FOUND) {
				throw new CachedObjectNotFoundException();
			}

			return $object;

		} elseif ($object = $db->queryRow($query)) {
			if ($expires === Cache::DO_NOT_CACHE) {
				return $object;
			} else {
				return $this->cacheByQuery($query, $object, $expires);
			}
		} else {
			throw new ObjectNotFoundException("zero" . (defined('__LOCAL_DEBUG__') ? "for query == " . $query->toDialectString(DBPool::me()->getByDao($this->dao)->getDialect()) : null));
		}
	}
	//@}

	/// object's list getters
	//@{
	public function getListByIds(array $ids, $expires = Cache::EXPIRES_MEDIUM) {
		$list = [];

		// dupes, if any, will be resolved later @ ArrayUtils::regularizeList
		$ids = array_unique($ids);

		if ($expires !== Cache::DO_NOT_CACHE) {
			$toFetch = [];
			$prefixed = [];

			foreach ($ids as $id) {
				$prefixed[$id] = $this->makeIdKey($id);
			}

			if ($cachedList = Cache::me()->mark($this->className)->getList($prefixed)) {
				$proto = $this->dao->getProtoClass();

				$proto->beginPrefetch();

				foreach ($cachedList as $cached) {
					if ($cached && ($cached !== Cache::NOT_FOUND)) {
						$list[] = $this->dao->completeObject($cached);

						unset($prefixed[$cached->getId()]);
					}
				}

				$proto->endPrefetch($list);
			}

			$toFetch += array_keys($prefixed);

			if ($toFetch) {
				try {
					$list = array_merge($list, $this->getListByLogic(Expression::in(new DBField($this->dao->getIdName(), $this->dao->getTable()), $toFetch), Cache::DO_NOT_CACHE));
				} catch (ObjectNotFoundException $e) {
					// nothing to fetch
				}
			}
		} elseif (count($ids)) {
			try {
				$list = $this->getListByLogic(Expression::in(new DBField($this->dao->getIdName(), $this->dao->getTable()), $ids), Cache::DO_NOT_CACHE);
			} catch (ObjectNotFoundException $e) {/*_*/
			}
		}

		return $list;
	}

	public function getListByQuery(SelectQuery $query, $expires = Cache::DO_NOT_CACHE) {
		if (($expires !== Cache::DO_NOT_CACHE) && ($list = $this->getCachedByQuery($query))) {
			if ($list === Cache::NOT_FOUND) {
				throw new CachedObjectNotFoundException();
			}

			return $list;
		} elseif ($list = $this->fetchList($query)) {
			if (Cache::DO_NOT_CACHE === $expires) {
				return $list;
			} else {
				return $this->cacheByQuery($query, $list, $expires);
			}
		} else {
			throw new ObjectNotFoundException("empty list" . (defined('__LOCAL_DEBUG__') ? " for such query - " . $query->toDialectString(DBPool::me()->getByDao($this->dao)->getDialect()) : null));
		}
	}

	public function getListByLogic(LogicalObject $logic, $expires = Cache::DO_NOT_CACHE) {
		return $this->getListByQuery($this->dao->makeSelectHead()->andWhere($logic), $expires);
	}

	public function getPlainList($expires = Cache::EXPIRES_MEDIUM) {
		return $this->getListByQuery($this->dao->makeSelectHead(), $expires);
	}

	public function getTotalCount($expires = Cache::DO_NOT_CACHE) {
		$count = $this->getCustom($this->dao->makeTotalCountQuery(), $expires);

		return current($count);
	}
	//@}

	/// custom list getters
	//@{
	public function getCustomList(SelectQuery $query, $expires = Cache::DO_NOT_CACHE) {
		if (($expires !== Cache::DO_NOT_CACHE) && ($list = $this->getCachedByQuery($query))) {
			if ($list === Cache::NOT_FOUND) {
				throw new CachedObjectNotFoundException();
			}

			return $list;
		} elseif ($list = DBPool::getByDao($this->dao)->querySet($query)) {
			if (Cache::DO_NOT_CACHE === $expires) {
				return $list;
			} else {
				return $this->cacheByQuery($query, $list, $expires);
			}
		} else {
			throw new ObjectNotFoundException("empty list" . (defined('__LOCAL_DEBUG__') ? " for such query - " . $query->toDialectString(DBPool::me()->getByDao($this->dao)->getDialect()) : null));
		}
	}

	public function getCustomRowList(SelectQuery $query, $expires = Cache::DO_NOT_CACHE) {
		if ($query->getFieldsCount() !== 1) {
			throw new WrongArgumentException('you should select only one row when using this method');
		}

		if (($expires !== Cache::DO_NOT_CACHE) && ($list = $this->getCachedByQuery($query))) {
			if ($list === Cache::NOT_FOUND) {
				throw new CachedObjectNotFoundException();
			}

			return $list;
		} elseif ($list = DBPool::getByDao($this->dao)->queryColumn($query)) {
			if (Cache::DO_NOT_CACHE === $expires) {
				return $list;
			} else {
				return $this->cacheByQuery($query, $list, $expires);
			}
		} else {
			throw new ObjectNotFoundException("empty list" . (defined('__LOCAL_DEBUG__') ? " for such query - " . $query->toDialectString(DBPool::me()->getByDao($this->dao)->getDialect()) : null));
		}
	}
	//@}

	/// query result getters
	//@{
	public function getQueryResult(SelectQuery $query, $expires = Cache::DO_NOT_CACHE) {
		if (($expires !== Cache::DO_NOT_CACHE) && ($list = $this->getCachedByQuery($query))) {
			return $list;
		} else {
			$list = $this->fetchList($query);

			$count = clone $query;

			$count = DBPool::getByDao($this->dao)->queryRow($count->dropFields()->dropOrder()->limit(null, null)->get(SQLFunction::create('COUNT', '*')->setAlias('count')));

			return $this->cacheByQuery($query,

				$list ? QueryResult::create()->setList($list)->setCount($count['count'])->setQuery($query) : QueryResult::create(),

				$expires);
		}
	}
	//@}

	/// cachers
	//@{
	protected function cacheById(Identifiable $object, $expires = Cache::EXPIRES_MEDIUM) {
		if ($expires !== Cache::DO_NOT_CACHE) {

			Cache::me()->mark($this->className)->add($this->makeIdKey($object->getId()), $object, $expires);
		}

		return $object;
	}

	protected function cacheByQuery(SelectQuery $query, /* Identifiable */
	                                $object, $expires = Cache::DO_NOT_CACHE) {
		if ($expires !== Cache::DO_NOT_CACHE) {

			Cache::me()->mark($this->className)->add($this->makeQueryKey($query, self::SUFFIX_QUERY), $object, $expires);
		}

		return $object;
	}

	protected function cacheListByQuery(SelectQuery $query, /* array || Cache::NOT_FOUND */
	                                    $array) {
		throw new UnimplementedFeatureException();
	}
	//@}

	/// erasers
	//@{
	public function dropById($id) {
		$result = parent::dropById($id);

		$this->dao->uncacheLists();

		return $result;
	}
	//@}

	/// uncachers
	//@{

	/**
	 * @return UncacherBase
	 */
	public function getUncacherById($id) {
		return UncacherCommonDaoWorker::create($this->className, $this->makeIdKey($id));
	}

	public function uncacheByIds($ids) {
		if (empty($ids)) {
			return;
		}

		$uncacher = $this->getUncacherById(array_shift($ids));

		foreach ($ids as $id) {
			$uncacher->merge($this->getUncacherById($id));
		}

		return $this->registerUncacher($uncacher->uncache());
	}

	// quite useless here
	public function uncacheLists() {
		// wipes getPlainList result, if any
		return $this->uncacheByQuery($this->dao->makeSelectHead());
	}
	//@}
}
