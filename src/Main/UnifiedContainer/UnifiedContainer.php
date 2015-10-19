<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\UnifiedContainer;

	/*
		UnifiedContainer:

			child's and parent's field names:
				abstract public function getChildIdField()
				abstract public function getParentIdField()

			all we need from outer world:
				public function __construct(
					Identifiable $parent, UnifiedContainer $dao, $lazy = true
				)

			if you want to apply Criteria's "filter":
				public function setCriteria(Criteria $criteria)

			first you should fetch whatever you want:
				public function fetch()

			then you can get it:
				public function getList()

			set you modified list:
				public function setList($list)

			finally, sync fetched data and stored one:
				public function save()

		OneToManyLinked <- UnifiedContainer:

			indicates whether child can be free (parent_id nullable):
				protected function isUnlinkable()

		ManyToManyLinked <- UnifiedContainer:

			helper's table name:
				abstract public function getHelperTable()

			id field name at parent's primary table:
				protected function getParentTableIdField()
	*/

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Identifiable;
use Hesper\Core\Base\Singleton;
use Hesper\Core\DB\Transaction\InnerTransactionWrapper;
use Hesper\Core\Exception\ObjectNotFoundException;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Exception\WrongStateException;
use Hesper\Main\Base\Comparator;
use Hesper\Main\Base\SerializedObjectComparator;
use Hesper\Main\Criteria\Criteria;
use Hesper\Main\DAO\GenericDAO;

/**
 * IdentifiableObject childs collection handling.
 * @see     StorableContainer for alternative
 * @package Hesper\Main\UnifiedContainer
 */
abstract class UnifiedContainer {

	/** @var UnifiedContainerWorker|null */
	protected $worker = null;
	protected $parent = null;

	protected $dao = null;

	protected $lazy    = true;
	protected $fetched = false;

	protected $list   = [];
	protected $clones = [];

	// sleep state
	protected $workerClass = null;
	protected $daoClass    = null;

	protected $comparator = null;

	abstract public function getParentIdField();

	abstract public function getChildIdField();

	public function __construct(Identifiable $parent, GenericDAO $dao, $lazy = true) {
		Assert::isBoolean($lazy);

		$this->parent = $parent;
		$this->lazy = $lazy;
		$this->dao = $dao;

		Assert::isInstance($dao->getObjectName(), Identifiable::class);

		$this->comparator = SerializedObjectComparator::me();
	}

	public function __sleep() {
		$this->daoClass = get_class($this->dao);
		$this->workerClass = get_class($this->worker);

		return ['workerClass', 'daoClass', 'parent', 'lazy'];
	}

	public function __wakeup() {
		$this->dao = Singleton::getInstance($this->daoClass);
		$this->worker = new $this->workerClass($this);
	}

	public function __clone() {
		$this->worker = clone $this->worker;
	}

	public function getParentObject() {
		return $this->parent;
	}

	/**
	 * @return GenericDAO
	 **/
	public function getDao() {
		return $this->dao;
	}

	public function isLazy() {
		return $this->lazy;
	}

	public function isFetched() {
		return $this->fetched;
	}

	/**
	 * @throws WrongArgumentException
	 * @return UnifiedContainer
	 **/
	public function setCriteria(Criteria $criteria) {
		Assert::isTrue($criteria->getDao() === null || ($criteria->getDao() === $this->dao), "criteria's dao doesn't match container's one");

		if (!$criteria->getDao()) {
			$criteria->setDao($this->dao);
		}

		$this->worker->setCriteria($criteria);

		return $this;
	}

	/**
	 * @return Criteria
	 **/
	public function getCriteria() {
		return $this->worker->getCriteria();
	}

	public function setObjectComparator(Comparator $comparator) {
		$this->comparator = $comparator;

		return $this;
	}

	/**
	 * @throws WrongArgumentException
	 * @return UnifiedContainer
	 **/
	public function setList($list) {
		Assert::isArray($list);

		$this->list = $list;

		return $this;
	}

	/**
	 * @return UnifiedContainer
	 **/
	public function mergeList(array $list) {
		if ($this->lazy) {
			foreach ($list as $id) {
				$this->list[$id] = $id;
			}
		} else {
			$this->list = array_merge($this->list, $list);
		}

		$this->fetched = true;

		return $this;
	}

	public function getList() {
		if (!$this->list && !$this->isFetched()) {
			$this->fetch();
		}

		return $this->list;
	}

	public function getCount() {
		if (!$this->isFetched() && $this->parent->getId()) {
			$row = $this->dao->getCustom($this->worker->makeCountQuery());

			return current($row);
		}

		return count($this->list);
	}

	/**
	 * @throws WrongStateException
	 * @return UnifiedContainer
	 **/
	public function fetch() {
		if (!$this->parent->getId()) {
			throw new WrongStateException('save parent object first');
		}

		try {
			$this->fetchList();
		} catch (ObjectNotFoundException $e) {
			// yummy
		}

		$this->fetched = true;

		return $this;
	}

	/**
	 * @throws WrongArgumentException
	 * @return UnifiedContainer
	 **/
	public function save() {
		Assert::isArray($this->list, "that's not an array :-/");

		if (!$this->fetched) {
			throw new WrongStateException('do not want to save non-fetched collection');
		}

		$list = $this->list;
		$clones = $this->clones;

		$ids = $insert = $delete = $update = [];

		if ($this->lazy) {
			foreach ($list as $id) {
				if (!isset($clones[$id])) {
					$insert[] = $ids[$id] = $id;
				} else {
					$ids[$id] = $id;
				}
			}

			foreach ($clones as $id) {
				if (!isset($ids[$id])) {
					$delete[] = $id;
				}
			}
		} else {
			foreach ($list as $object) {
				$id = $object->getId();

				if (null === $id) {
					$insert[] = $object;
				} elseif (isset($clones[$id])
					// there is no another way yet to compare objects without
					// risk of falling into fatal error:
					// "nesting level too deep?"
					&& ($this->comparator->compare($object, $clones[$id]) <> 0)
				) {
					$update[] = $object;
				} elseif (!isset($clones[$id])) {
					$insert[] = $object;
				}

				if (null !== $id) {
					$ids[$id] = $object;
				}
			}

			foreach ($clones as $id => $object) {
				if (!isset($ids[$id])) {
					$delete[] = $object;
				}
			}
		}

		InnerTransactionWrapper::create()
		                       ->setDao($this->getDao())
		                       ->setFunction([$this->worker, 'sync'])
		                       ->run($insert, $update, $delete);

		$this->clones = [];
		$this->syncClones();
		$this->dao->uncacheLists();

		return $this;
	}

	/**
	 * @return UnifiedContainer
	 **/
	public function clean() {
		$this->list = $this->clones = [];

		$this->fetched = false;

		return $this;
	}

	/**
	 * @return UnifiedContainer
	 **/
	public function dropList() {
		$this->worker->dropList();

		$this->clean();

		return $this;
	}

	/* void */
	public static function destroy(UnifiedContainer $container) {
		unset($container->worker, $container);
	}

	protected function fetchList() {
		$query = $this->worker->makeFetchQuery();

		if ($this->lazy) {
			$list = $this->dao->getCustomRowList($query);

			// special case for handling result from db
			if (isset($list[0]) && is_array($list[0])) {
				$newList = [];

				foreach ($list as $key => $value) {
					$newList[] = $value[$this->getChildIdField()];
				}

				$list = $newList;
			}
		} else {
			$list = $this->dao->getListByQuery($query);
		}

		$this->list = [];

		return $this->importList($list);
	}

	/**
	 * @return UnifiedContainer
	 **/
	private function importList(array $list) {
		$this->mergeList($list);

		$this->syncClones();

		return $this;
	}

	/**
	 * @return UnifiedContainer
	 **/
	private function syncClones() {
		if ($this->lazy) {
			foreach ($this->list as $id) {
				$this->clones[$id] = $id;
			}
		} else {
			foreach ($this->list as $object) {
				// don't track unsaved objects
				if ($id = $object->getId()) {
					$this->clones[$id] = clone $object;
				}
			}
		}

		return $this;
	}
}
