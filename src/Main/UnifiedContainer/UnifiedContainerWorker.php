<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\UnifiedContainer;

use Hesper\Core\DB\DBPool;
use Hesper\Core\Logic\Expression;
use Hesper\Core\OSQL\DBField;
use Hesper\Core\OSQL\DBValue;
use Hesper\Core\OSQL\OSQL;
use Hesper\Core\OSQL\SelectQuery;
use Hesper\Core\OSQL\SQLFunction;
use Hesper\Main\Criteria\Criteria;

/**
 * Class UnifiedContainerWorker
 * @see     UnifiedContainer
 * @package Hesper\Main\UnifiedContainer
 */
abstract class UnifiedContainerWorker {

	/** @var Criteria */
	protected $criteria  = null;
	/** @var UnifiedContainer */
	protected $container = null;

	/**
	 * @return SelectQuery
	 */
	abstract public function makeFetchQuery();

	abstract public function sync($insert, $update = [], $delete);

	public function __construct(UnifiedContainer $uc) {
		$this->container = $uc;
	}

	public function __clone() {
		if( $this->criteria ) {
			$this->criteria = clone $this->criteria;
		}
	}

	/**
	 * @return UnifiedContainerWorker
	 **/
	public function setCriteria(Criteria $criteria) {
		$this->criteria = $criteria;

		return $this;
	}

	/**
	 * @return Criteria
	 **/
	public function getCriteria() {
		return $this->criteria;
	}

	/**
	 * @return SelectQuery
	 **/
	public function makeCountQuery() {
		$query = $this->makeFetchQuery();

		if ($query->isDistinct()) {
			$countFunction =
				SQLFunction::create(
					'count',
					DBField::create(
						$this->container->getDao()->getIdName(), $this->container->getDao()->getTable()
					)
				)->setAggregateDistinct();

			$query->unDistinct();

		} else {
			$countFunction = SQLFunction::create('count', DBValue::create('*'));
		}

		return $query->dropFields()
		             ->dropOrder()
		             ->dropLimit()
		             ->get($countFunction->setAlias('count'));
	}

	public function dropList() {
		$dao = $this->container->getDao();

		DBPool::getByDao($dao)
			->queryNull(
				OSQL::delete()
					->from($this->container->getHelperTable())
					->where(Expression::eq($this->container->getParentIdField(), $this->container->getParentObject()->getId()))
			);

		$dao->uncacheLists();

		return $this;
	}

	/**
	 * @return SelectQuery
	 **/
	protected function makeSelectQuery() {
		if ($this->criteria) {
			return $this->criteria->toSelectQuery();
		}

		return $this->container->getDao()->makeSelectHead();
	}
}
