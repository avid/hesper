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
use Hesper\Core\OSQL\OSQL;
use Hesper\Core\OSQL\SelectQuery;
use Hesper\Main\Util\ArrayUtils;

/**
 * @ingroup Containers
**/
final class OneToManyLinkedFull extends OneToManyLinkedWorker
{
	/**
	 * @return SelectQuery
	**/
	public function makeFetchQuery()
	{
		return $this->targetize($this->makeSelectQuery());
	}

	/**
	 * @return OneToManyLinkedFull
	**/
	public function sync($insert, $update = array(), $delete)
	{
		$uc = $this->container;
		$dao = $uc->getDao();

		if ($delete) {
			DBPool::getByDao($dao)->queryNull(
				OSQL::delete()->from($dao->getTable())->
				where(
					Expression::eq(
						new DBField($uc->getParentIdField()),
						$uc->getParentObject()->getId()
					)
				)->
				andWhere(
					Expression::in(
						$uc->getChildIdField(),
						ArrayUtils::getIdsArray($delete)
					)
				)
			);

			$dao->uncacheByIds(ArrayUtils::getIdsArray($delete));
		}

		if ($insert)
			for ($i = 0, $size = count($insert); $i < $size; ++$i)
				$dao->add($insert[$i]);

		if ($update)
			for ($i = 0, $size = count($update); $i < $size; ++$i)
				$dao->save($update[$i]);

		return $this;
	}
}