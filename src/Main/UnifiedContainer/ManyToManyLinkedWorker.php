<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\UnifiedContainer;

use Hesper\Core\Logic\Expression;
use Hesper\Core\OSQL\DBField;
use Hesper\Core\OSQL\DBValue;
use Hesper\Core\OSQL\DeleteQuery;
use Hesper\Core\OSQL\InsertQuery;
use Hesper\Core\OSQL\OSQL;
use Hesper\Core\OSQL\SelectQuery;

/**
 * Class ManyToManyLinkedWorker
 * @package Hesper\Main\UnifiedContainer
 */
abstract class ManyToManyLinkedWorker extends UnifiedContainerWorker {

	/**
	 * @return InsertQuery
	 **/
	protected function makeInsertQuery($childId) {
		$uc = $this->container;

		return OSQL::insert()
		           ->into($uc->getHelperTable())
		           ->set($uc->getParentIdField(), $uc->getParentObject()
		                                             ->getId())
		           ->set($uc->getChildIdField(), $childId);
	}

	/**
	 * only unlinking, we don't want to drop original object
	 * @return DeleteQuery
	 **/
	protected function makeDeleteQuery($delete) {
		$uc = $this->container;

		return OSQL::delete()
		           ->from($uc->getHelperTable())
		           ->where(Expression::eq(new DBField($uc->getParentIdField()), new DBValue($uc->getParentObject()->getId())))
		           ->andWhere(Expression::in($uc->getChildIdField(), $delete));
	}

	/**
	 * @return SelectQuery
	 **/
	protected function joinHelperTable(SelectQuery $query) {
		$uc = $this->container;

		if (!$query->hasJoinedTable($uc->getHelperTable())) {
			$query->join($uc->getHelperTable(), Expression::eq(new DBField($uc->getParentTableIdField(), $uc->getDao()
			                                                                                                ->getTable()), new DBField($uc->getChildIdField(), $uc->getHelperTable())));
		}

		return $query->andWhere(Expression::eq(new DBField($uc->getParentIdField(), $uc->getHelperTable()), new DBValue($uc->getParentObject()
		                                                                                                                   ->getId())));
	}
}
