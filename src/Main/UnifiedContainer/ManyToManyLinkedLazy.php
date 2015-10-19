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
use Hesper\Core\OSQL\DBField;
use Hesper\Core\OSQL\SelectQuery;

/**
 * @ingroup Containers
**/
final class ManyToManyLinkedLazy extends ManyToManyLinkedWorker
{
	/**
	 * @throws WrongArgumentException
	 * @return ManyToManyLinkedLazy
	**/
	public function sync($insert, $update = array(), $delete)
	{
		Assert::isTrue($update === array());

		$dao = $this->container->getDao();

		$db = DBPool::getByDao($dao);

		if ($insert)
			for ($i = 0, $size = count($insert); $i < $size; ++$i)
				$db->queryNull($this->makeInsertQuery($insert[$i]));

		if ($delete) {
			$db->queryNull($this->makeDeleteQuery($delete));

			$dao->uncacheByIds($delete);
		}

		return $this;
	}

	/**
	 * @return SelectQuery
	**/
	public function makeFetchQuery()
	{
		$uc = $this->container;

		return
			$this->joinHelperTable(
				$this->makeSelectQuery()->
				dropFields()->
				get(
					new DBField(
						$uc->getChildIdField(),
						$uc->getHelperTable()
					)
				)
			);
	}
}
