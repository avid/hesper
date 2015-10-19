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
use Hesper\Core\OSQL\SelectQuery;

/**
 * @ingroup Containers
 **/
abstract class OneToManyLinkedWorker extends UnifiedContainerWorker {

	/**
	 * @return SelectQuery
	 **/
	protected function targetize(SelectQuery $query) {
		return $query->andWhere(
			Expression::eqId(
				new DBField($this->container->getParentIdField(),$this->container->getDao()->getTable()),
				$this->container->getParentObject())
		);
	}
}
