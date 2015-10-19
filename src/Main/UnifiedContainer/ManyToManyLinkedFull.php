<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\UnifiedContainer;

use Hesper\Core\DB\DBPool;
use Hesper\Core\OSQL\SelectQuery;

/**
 * Class ManyToManyLinkedFull
 * @package Hesper\Main\UnifiedContainer
 */
final class ManyToManyLinkedFull extends ManyToManyLinkedWorker {

	/**
	 * @return ManyToManyLinkedFull
	 **/
	public function sync($insert, $update = [], $delete) {
		$dao = $this->container->getDao();

		$db = DBPool::getByDao($dao);

		if ($insert) {
			for ($i = 0, $size = count($insert); $i < $size; ++$i) {
				$db->queryNull($this->makeInsertQuery($dao->take($insert[$i])
				                                          ->getId()));
			}
		}

		if ($update) {
			for ($i = 0, $size = count($update); $i < $size; ++$i) {
				$dao->save($update[$i]);
			}
		}

		if ($delete) {
			$ids = [];

			foreach ($delete as $object) {
				$ids[] = $object->getId();
			}

			$db->queryNull($this->makeDeleteQuery($ids));

			$dao->uncacheByIds($ids);
		}

		return $this;
	}

	/**
	 * @return SelectQuery
	 **/
	public function makeFetchQuery() {
		return $this->joinHelperTable($this->makeSelectQuery());
	}
}
