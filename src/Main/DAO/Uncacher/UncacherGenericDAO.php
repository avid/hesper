<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Aleksey S. Denisov
 */
namespace Hesper\Main\DAO\Uncacher;

use Hesper\Core\Base\Assert;
use Hesper\Main\DAO\GenericDAO;
use Hesper\Main\Util\ArrayUtils;

/**
 * Class UncacherGenericDAO
 * @package Hesper\Main\DAO\Uncacher
 */
class UncacherGenericDAO implements UncacherBase {

	private $daoMap = [];

	public static function create(GenericDAO $dao, $id, UncacherBase $workerUncacher) {
		return new self($dao, $id, $workerUncacher);
	}

	public function __construct(GenericDAO $dao, $id, UncacherBase $workerUncacher) {
		$this->daoMap[get_class($dao)] = [[$id], $workerUncacher];
	}

	public function getDaoMap() {
		return $this->daoMap;
	}

	/**
	 * @param $uncacher UncacherGenericDAO same as self class
	 *
	 * @return UncacherBase (this)
	 */
	public function merge(UncacherBase $uncacher) {
		Assert::isInstance($uncacher, self::class);

		return $this->mergeSelf($uncacher);
	}

	public function uncache() {
		foreach ($this->daoMap as $daoClass => $uncacheData) {
			$dao = GenericDAO::getInstance($daoClass);
			/* @var $dao GenericDAO */
			list($dropIdentityIds, $workerUncacher) = $uncacheData;
			/* @var $workerUncacher UncacherBase */

			foreach ($dropIdentityIds as $id) {
				$dao->dropObjectIdentityMapById($id);
			}

			$dao->registerWorkerUncacher($workerUncacher);
		}
	}

	private function mergeSelf(UncacherGenericDAO $uncacher) {
		foreach ($uncacher->getDaoMap() as $daoClass => $daoMap) {
			if (isset($this->daoMap[$daoClass])) {
				//merge identities
				$this->daoMap[$daoClass][0] = ArrayUtils::mergeUnique($this->daoMap[$daoClass][0], $daoMap[0]);
				//merge workers uncachers
				$this->daoMap[$daoClass][1]->merge($daoMap[1]);
			} else {
				$this->daoMap[$daoClass] = $daoMap;
			}
		}

		return $this;
	}
}
