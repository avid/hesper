<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\DAO;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Identifiable;
use Hesper\Core\Base\Singleton;
use Hesper\Core\Cache\Cache;
use Hesper\Core\DB\DBPool;
use Hesper\Core\Exception\WrongStateException;
use Hesper\Core\Logic\LogicalObject;
use Hesper\Core\OSQL\DBField;
use Hesper\Core\OSQL\DBValue;
use Hesper\Core\OSQL\InsertOrUpdateQuery;
use Hesper\Core\OSQL\OSQL;
use Hesper\Core\OSQL\SelectQuery;
use Hesper\Core\OSQL\SQLFunction;
use Hesper\Main\Base\AbstractProtoClass;
use Hesper\Main\DAO\Events\OnAfterDrop;
use Hesper\Main\DAO\Events\OnAfterSave;
use Hesper\Main\DAO\Events\OnBeforeDrop;
use Hesper\Main\DAO\Events\OnBeforeSave;
use Hesper\Main\DAO\Uncacher\UncacherBase;
use Hesper\Main\DAO\Uncacher\UncacherGenericDAO;
use Hesper\Main\DAO\Uncacher\UncachersPool;
use Hesper\Main\Util\ArrayUtils;
use Hesper\Main\Util\ClassUtils;

/**
 * Basis of all DAO's.
 * @package Hesper\Main\DAO
 */
abstract class GenericDAO extends Singleton implements BaseDAO {

	private $identityMap = [];

	protected $linkName = null;

	private $triggersAllowed = true;

	abstract public function getTable();

	abstract public function getObjectName();

	public function makeObject($array, $prefix = null) {
		if (isset($this->identityMap[$array[$idName = $prefix . $this->getIdName()]])) {
			$this->getProtoClass()->skipObjectPrefetching($this->identityMap[$array[$idName]]);

			return $this->identityMap[$array[$idName]];
		}

		return $this->completeObject($this->makeOnlyObject($array, $prefix));
	}

	public function makeOnlyObject($array, $prefix = null) {
		// adding incomplete object to identity map
		// solves case with circular-dependent objects
		return $this->addObjectToMap($this->getProtoClass()->makeOnlyObject($this->getObjectName(), $array, $prefix));
	}

	public function completeObject(Identifiable $object) {
		return $this->getProtoClass()->completeObject(// same purpose as in makeOnlyObject,
			// but for objects retrieved from cache
				$this->addObjectToMap($object));
	}

	/**
	 * Returns link name which is used to get actual DB-link from DBPool,
	 * returning null by default for single-source projects.
	 * @see DBPool
	 **/
	public function getLinkName() {
		return $this->linkName;
	}

	public function getIdName() {
		return 'id';
	}

	public function getSequence() {
		return $this->getTable() . '_id';
	}

	/**
	 * @return AbstractProtoClass
	 **/
	public function getProtoClass() {
		static $protos = [];

		if (!isset($protos[$className = $this->getObjectName()])) {
			$protos[$className] = call_user_func([$className, 'proto']);
		}

		return $protos[$className];
	}

	public function getMapping() {
		return $this->getProtoClass()->getMapping();
	}

	public function getFields() {
		static $fields = [];

		$className = $this->getObjectName();

		if (!isset($fields[$className])) {
			$fields[$className] = array_values($this->getMapping());
		}

		return $fields[$className];
	}

	/**
	 * @return SelectQuery
	 **/
	public function makeSelectHead() {
		static $selectHead = [];

		if (!isset($selectHead[$className = $this->getObjectName()])) {
			$table = $this->getTable();

			$object = OSQL::select()->from($table);

			foreach ($this->getFields() as $field) {
				$object->get(new DBField($field, $table));
			}

			$selectHead[$className] = $object;
		}

		return clone $selectHead[$className];
	}

	/**
	 * @return SelectQuery
	 **/
	public function makeTotalCountQuery() {
		return OSQL::select()->get(SQLFunction::create('count', DBValue::create('*')))->from($this->getTable());
	}

	/// boring delegates
	//@{
	public function getById($id, $expires = Cache::EXPIRES_MEDIUM) {
		Assert::isScalar($id);
		Assert::isNotEmpty($id);

		if (isset($this->identityMap[$id])) {
			return $this->identityMap[$id];
		}

		return $this->addObjectToMap(Cache::worker($this)->getById($id, $expires));
	}

	public function getByLogic(LogicalObject $logic, $expires = Cache::DO_NOT_CACHE) {
		return $this->addObjectToMap(Cache::worker($this)->getByLogic($logic, $expires));
	}

	public function getByQuery(SelectQuery $query, $expires = Cache::DO_NOT_CACHE) {
		return $this->addObjectToMap(Cache::worker($this)->getByQuery($query, $expires));
	}

	public function getCustom(SelectQuery $query, $expires = Cache::DO_NOT_CACHE) {
		return Cache::worker($this)->getCustom($query, $expires);
	}

	public function getListByIds(array $ids, $expires = Cache::EXPIRES_MEDIUM) {
		$mapped = $remain = [];

		foreach ($ids as $id) {
			if (isset($this->identityMap[$id])) {
				$mapped[] = $this->identityMap[$id];
			} else {
				$remain[] = $id;
			}
		}

		if ($remain) {
			$list = $this->addObjectListToMap(Cache::worker($this)->getListByIds($remain, $expires));

			$mapped = array_merge($mapped, $list);
		}

		return ArrayUtils::regularizeList($ids, $mapped);
	}

	public function getListByQuery(SelectQuery $query, $expires = Cache::DO_NOT_CACHE) {
		return $this->addObjectListToMap(Cache::worker($this)->getListByQuery($query, $expires));
	}

	public function getListByLogic(LogicalObject $logic, $expires = Cache::DO_NOT_CACHE) {
		return $this->addObjectListToMap(Cache::worker($this)->getListByLogic($logic, $expires));
	}

	public function getPlainList($expires = Cache::EXPIRES_MEDIUM) {
		return $this->addObjectListToMap(Cache::worker($this)->getPlainList($expires));
	}

	public function getTotalCount($expires = Cache::DO_NOT_CACHE) {
		return Cache::worker($this)->getTotalCount($expires);
	}

	public function getCustomList(SelectQuery $query, $expires = Cache::DO_NOT_CACHE) {
		return Cache::worker($this)->getCustomList($query, $expires);
	}

	public function getCustomRowList(SelectQuery $query, $expires = Cache::DO_NOT_CACHE) {
		return Cache::worker($this)->getCustomRowList($query, $expires);
	}

	public function getQueryResult(SelectQuery $query, $expires = Cache::DO_NOT_CACHE) {
		return Cache::worker($this)->getQueryResult($query, $expires);
	}

	public function drop(Identifiable $object) {
		$this->checkObjectType($object);

		return $this->dropById($object->getId());
	}

	public function dropById($id) {
		call_user_func($this->prepareTrigger($id, OnBeforeDrop::class));
		$after = $this->prepareTrigger($id, OnAfterDrop::class);

		unset($this->identityMap[$id]);

		$count = Cache::worker($this)->dropById($id);

		call_user_func($after);

		if (1 != $count) {
			throw new WrongStateException('no object were dropped');
		}

		return $count;
	}

	public function dropByIds(array $ids) {
		call_user_func($this->prepareTrigger($ids, OnBeforeDrop::class));
		$after = $this->prepareTrigger($ids, OnAfterDrop::class);

		foreach ($ids as $id) {
			unset($this->identityMap[$id]);
		}

		$count = Cache::worker($this)->dropByIds($ids);

		call_user_func($after);

		if ($count != count($ids)) {
			throw new WrongStateException('not all objects were dropped');
		}

		return $count;
	}

	public function uncacheById($id) {
		return $this->getUncacherById($id)->uncache();
	}

	/**
	 * @return UncachersPool
	 */
	public function getUncacherById($id) {
		return UncacherGenericDAO::create($this, $id, Cache::worker($this)->getUncacherById($id));
	}

	public function uncacheByIds($ids) {
		if (empty($ids)) {
			return;
		}

		$uncacher = $this->getUncacherById(array_shift($ids));

		foreach ($ids as $id) {
			$uncacher->merge($this->getUncacherById($id));
		}

		return $uncacher->uncache();
	}

	public function uncacheLists() {
		$this->dropIdentityMap();

		return Cache::worker($this)->uncacheLists();
	}
	//@}

	/**
	 * @return GenericDAO
	 **/
	public function dropIdentityMap() {
		$this->identityMap = [];

		return $this;
	}

	public function dropObjectIdentityMapById($id) {
		unset($this->identityMap[$id]);

		return $this;
	}

	public function registerWorkerUncacher(UncacherBase $uncacher) {
		DBPool::getByDao($this)->registerUncacher($uncacher);
	}

	protected function inject(InsertOrUpdateQuery $query, Identifiable $object) {
		$this->checkObjectType($object);

		$this->runTrigger($object, OnBeforeSave::class);

		return $this->doInject($this->setQueryFields($query->setTable($this->getTable()), $object), $object);
	}

	protected function doInject(InsertOrUpdateQuery $query, Identifiable $object) {
		$db = DBPool::getByDao($this);

		if (!$db->isQueueActive()) {
			$preUncacher = is_scalar($object->getId()) ? $this->getUncacherById($object->getId()) : null;

			$count = $db->queryCount($query);

			$uncacher = $this->getUncacherById($object->getId());
			if ($preUncacher) {
				$uncacher->merge($uncacher);
			}
			$uncacher->uncache();

			if ($count !== 1) {
				throw new WrongStateException($count . ' rows affected: racy or insane inject happened: ' . $query->toDialectString($db->getDialect()));
			}
		} else {
			$preUncacher = is_scalar($object->getId()) ? $this->getUncacherById($object->getId()) : null;

			$db->queryNull($query);

			$uncacher = $this->getUncacherById($object->getId());
			if ($preUncacher) {
				$uncacher->merge($uncacher);
			}
			$uncacher->uncache();
		}

		// clean out Identifier, if any
		$result = $this->addObjectToMap($object->setId($object->getId()));

		$this->runTrigger($object, OnAfterSave::class);

		return $result;
	}

	/* void */
	protected function checkObjectType(Identifiable $object) {
		Assert::isInstance($object, $this->getObjectName(), 'strange object given, i can not inject it');
//		Assert::isSame(get_class($object), $this->getObjectName(), 'strange object given, i can not inject it');
	}

	private function addObjectToMap(Identifiable $object) {
		return $this->identityMap[$object->getId()] = $object;
	}

	private function addObjectListToMap($list) {
		foreach ($list as $object) {
			$this->identityMap[$object->getId()] = $object;
		}

		return $list;
	}

	public function disableTriggers() {
		$this->triggersAllowed = false;
		return $this;
	}

	public function enableTriggers() {
		$this->triggersAllowed = true;
		return $this;
	}

	protected final function runTrigger($input, $triggerName) {
		call_user_func($this->prepareTrigger($input, $triggerName));

		return $this;
	}

	protected final function prepareTrigger($input, $triggerName) {
		$objName = $this->getObjectName();
		if(
			!$this->triggersAllowed ||
			!ClassUtils::isInstanceOf($objName, $triggerName)
		) {
			return (function(){ });
		}

		$method = explode('\\', $triggerName);
		$method = lcfirst(end($method));

		$check = function($obj) use ($objName) {
			if(!($obj instanceof $objName)) {
				$obj = $this->getById($obj);
			}
			return $obj;
		};

		if(is_array($input)) {
			$input = array_map($check, $input);
		} else {
			$input = array($check($input));
		}

		return function() use (&$input, $method) {
			foreach($input as $obj) {
				call_user_func(array($obj, $method));
			}
		};
	}
}
