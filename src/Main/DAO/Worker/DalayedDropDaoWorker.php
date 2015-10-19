<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Main\DAO\Worker;

use Hesper\Core\Base\Assert;
use Hesper\Main\DAO\Uncacher\UncacherBase;
use Hesper\Main\DAO\Uncacher\UncacherNullDaoWorker;

/**
 * DAO worker with dealyed object drop from cache
 * @see     CommonDaoWorker for manual-caching one.
 * @see     SmartDaoWorker for transparent one.
 * @package Hesper\Main\DAO\Worker
 */
final class DalayedDropDaoWorker extends NullDaoWorker {

	private $modifiedIds = [];

	/// uncachers
	//@{
	public function uncacheById($id) {
		$this->modifiedIds[$id] = $id;

		return true;
	}

	/**
	 * @param mixed $id
	 *
	 * @return UncacherBase
	 */
	public function getUncacherById($id) {
		return UncacherNullDaoWorker::create();
	}

	public function dropWith($worker) {
		Assert::classExists($worker);

		if ($this->modifiedIds) {
			$workerObject = new $worker($this->dao);

			$workerObject->uncacheByIds($this->modifiedIds);

			$this->modifiedIds = [];
		}

		return $this;
	}
	//@}
}
