<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\UnifiedContainer;

use Hesper\Core\Base\Assert;
use Hesper\Core\DB\DBPool;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Logic\Expression;
use Hesper\Core\OSQL\OSQL;
use Hesper\Core\OSQL\SelectQuery;
use Hesper\Core\OSQL\UpdateQuery;

/**
 * @ingroup Containers
**/
final class OneToManyLinkedLazy extends OneToManyLinkedWorker
{
	/**
	 * @return SelectQuery
	**/
	public function makeFetchQuery()
	{
		$query =
			$this->makeSelectQuery()->
			dropFields()->
			get($this->container->getChildIdField());

		return $this->targetize($query);
	}

	/**
	 * @throws WrongArgumentException
	 * @return OneToManyLinkedLazy
	**/
	public function sync($insert, $update = array(), $delete)
	{
		Assert::isTrue($update === array());

		$db = DBPool::getByDao($this->container->getDao());

		$uc = $this->container;
		$dao = $uc->getDao();

		if ($insert)
			$db->queryNull($this->makeMassUpdateQuery($insert));

		if ($delete) {
			// unlink or drop
			$uc->isUnlinkable()
				?
					$db->queryNull($this->makeMassUpdateQuery($delete))
				:
					$db->queryNull(
						OSQL::delete()->from($dao->getTable())->
						where(
							Expression::in(
								$uc->getChildIdField(),
								$delete
							)
						)
					);

			$dao->uncacheByIds($delete);
		}

		return $this;
	}

	/**
	 * @return UpdateQuery
	**/
	private function makeMassUpdateQuery($ids)
	{
		$uc = $this->container;

		return
			OSQL::update($uc->getDao()->getTable())->
			set($uc->getParentIdField(), null)->
			where(
				Expression::in(
					$uc->getChildIdField(),
					$ids
				)
			);
	}
}
