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
use Hesper\Core\Cache\WatermarkedPeer;
use Hesper\Core\DB\DBPool;
use Hesper\Core\Logic\Expression;
use Hesper\Core\OSQL\OSQL;
use Hesper\Core\OSQL\SelectQuery;
use Hesper\Main\DAO\BaseDAO;
use Hesper\Main\DAO\GenericDAO;
use Hesper\Main\DAO\Uncacher\UncacherBase;
use Hesper\Main\DAO\Uncacher\UncacherBaseDaoWorker;

/**
 * Class BaseDaoWorker
 * @package Hesper\Main\DAO\Uncacher
 */
abstract class BaseDaoWorker implements BaseDAO {

	const SUFFIX_LIST   = '_list_';
	const SUFFIX_INDEX  = '_lists_index_';
	const SUFFIX_QUERY  = '_query_';
	const SUFFIX_RESULT = '_result_';

	protected $dao = null;

	protected $className = null;

	protected $watermark = null;

	public function __construct(GenericDAO $dao) {
		$this->dao = $dao;

		$this->className = $dao->getObjectName();

		if (($cache = Cache::me()) instanceof WatermarkedPeer) {
			$this->watermark = $cache->mark($this->className)->getActualWatermark();
		}
	}

	/**
	 * @return BaseDaoWorker
	 **/
	public function setDao(GenericDAO $dao) {
		$this->dao = $dao;

		return $this;
	}

	/// erasers
	//@{
	public function drop(Identifiable $object) {
		return $this->dropById($object->getId());
	}

	public function dropById($id) {
		$result = DBPool::getByDao($this->dao)->queryCount(OSQL::delete()->from($this->dao->getTable())->where(Expression::eq($this->dao->getIdName(), $id)));

		$this->dao->uncacheById($id);

		return $result;
	}

	public function dropByIds(array $ids) {
		$result = DBPool::getByDao($this->dao)->queryCount(OSQL::delete()->from($this->dao->getTable())->where(Expression::in($this->dao->getIdName(), $ids)));

		$this->dao->uncacheByIds($ids);

		return $result;
	}
	//@}

	/// uncachers
	//@{
	public function uncacheById($id) {
		return $this->registerUncacher($this->getUncacherById($id));
	}

	/**
	 * @return UncacherBase
	 */
	public function getUncacherById($id) {
		return UncacherBaseDaoWorker::create($this->className, $this->makeIdKey($id));
	}

	public function uncacheByQuery(SelectQuery $query) {
		return $this->registerUncacher(UncacherBaseDaoWorker::create($this->className, $this->makeQueryKey($query, self::SUFFIX_QUERY)));
	}

	protected function registerUncacher(UncacherBase $uncacher) {
		return $this->dao->registerWorkerUncacher($uncacher);
	}
	//@}

	/// cache getters
	//@{
	public function getCachedById($id) {
		return Cache::me()->mark($this->className)->get($this->makeIdKey($id));
	}

	protected function getCachedByQuery(SelectQuery $query) {
		return Cache::me()->mark($this->className)->get($this->makeQueryKey($query, self::SUFFIX_QUERY));
	}

	//@}

	/// fetchers
	//@{
	protected function fetchObject(SelectQuery $query) {
		if ($row = DBPool::getByDao($this->dao)->queryRow($query)) {
			return $this->dao->makeObject($row);
		}

		return null;
	}

	protected function cachedFetchObject(SelectQuery $query, $expires, $byId = true) {
		if ($row = DBPool::getByDao($this->dao)->queryRow($query)) {
			$object = $this->dao->makeOnlyObject($row);

			if ($byId) {
				$object = $this->cacheById($object, $expires);
			} else {
				$object = $this->cacheByQuery($query, $object, $expires);
			}

			return $this->dao->completeObject($object);
		}

		return null;
	}

	protected function fetchList(SelectQuery $query) {
		$list = [];

		if ($rows = DBPool::getByDao($this->dao)->querySet($query)) {
			$proto = $this->dao->getProtoClass();

			$proto->beginPrefetch();

			foreach ($rows as $row) {
				$list[] = $this->dao->makeObject($row);
			}

			$proto->endPrefetch($list);
		}

		return $list;
	}

	//@}

	protected function makeIdKey($id) {
		return $this->className . '_' . $id . $this->watermark;
	}

	protected function makeQueryKey(SelectQuery $query, $suffix) {
		return $this->className . $suffix . $query->getId() . $this->watermark;
	}
}
