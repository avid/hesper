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
use Hesper\Core\Exception\ObjectNotFoundException;
use Hesper\Core\Logic\Expression;
use Hesper\Core\Logic\LogicalObject;
use Hesper\Core\OSQL\DBField;
use Hesper\Core\OSQL\SelectQuery;
use Hesper\Main\DAO\Uncacher\UncacherNullDaoWorker;

/**
 * Cacheless DAO worker.
 * @see     CommonDaoWorker for manual-caching one.
 * @see     SmartDaoWorker for transparent one.
 * @package Hesper\Main\DAO\Worker
 */
class NullDaoWorker extends CommonDaoWorker {

	/// single object getters
	//@{
	public function getById($id) {
		return parent::getById($id, Cache::DO_NOT_CACHE);
	}

	public function getByLogic(LogicalObject $logic) {
		return parent::getByLogic($logic, Cache::DO_NOT_CACHE);
	}

	public function getByQuery(SelectQuery $query) {
		return parent::getByQuery($query, Cache::DO_NOT_CACHE);
	}

	public function getCustom(SelectQuery $query) {
		return parent::getCustom($query, Cache::DO_NOT_CACHE);
	}
	//@}

	/// object's list getters
	//@{
	public function getListByIds(array $ids) {
		try {
			return $this->getListByLogic(Expression::in(new DBField($this->dao->getIdName(), $this->dao->getTable()), $ids));
		} catch (ObjectNotFoundException $e) {
			return [];
		}
	}

	public function getListByQuery(SelectQuery $query) {
		return parent::getListByQuery($query, Cache::DO_NOT_CACHE);
	}

	public function getListByLogic(LogicalObject $logic) {
		return parent::getListByLogic($logic, Cache::DO_NOT_CACHE);
	}

	public function getPlainList() {
		return parent::getPlainList(Cache::DO_NOT_CACHE);
	}
	//@}

	/// custom list getters
	//@{
	public function getCustomList(SelectQuery $query) {
		return parent::getCustomList($query, Cache::DO_NOT_CACHE);
	}

	public function getCustomRowList(SelectQuery $query) {
		return parent::getCustomRowList($query, Cache::DO_NOT_CACHE);
	}
	//@}

	/// query result getters
	//@{
	public function getQueryResult(SelectQuery $query) {
		return parent::getQueryResult($query, Cache::DO_NOT_CACHE);
	}
	//@}

	/// cachers
	//@{
	protected function cacheById(Identifiable $object, $expires = Cache::DO_NOT_CACHE) {
		return $object;
	}

	protected function cacheByQuery(SelectQuery $query, /* Identifiable */
	                                $object, $expires = Cache::DO_NOT_CACHE) {
		return $object;
	}
	//@}

	/// uncachers
	//@{
	public function uncacheById($id) {
		return true;
	}

	/**
	 * @return UncacherNullDaoWorker
	 */
	public function getUncacherById($id) {
		return UncacherNullDaoWorker::create();
	}

	public function uncacheByIds($ids) {
		return true;
	}

	public function uncacheByQuery(SelectQuery $query) {
		return true;
	}

	public function uncacheLists() {
		return true;
	}
	//@}

	/// cache getters
	//@{
	public function getCachedById($id) {
		return null;
	}

	protected function getCachedByQuery(SelectQuery $query) {
		return null;
	}
	//@}
}
