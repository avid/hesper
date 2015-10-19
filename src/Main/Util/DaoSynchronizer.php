<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Util;

use Hesper\Core\Base\Assert;
use Hesper\Main\DAO\GenericDAO;
use Hesper\Main\DAO\ProtoDAO;

/**
 * Prototyped variant of DAO synchronizer.
 * @package Hesper\Main\Util
 */
class DaoSynchronizer extends CustomizableDaoSynchronizer {

	/**
	 * @return DaoSynchronizer
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return DaoSynchronizer
	 **/
	public function setMaster(GenericDAO $master) {
		Assert::isInstance($master, ProtoDAO::class);

		return parent::setMaster($master);
	}

	/**
	 * @return DaoSynchronizer
	 **/
	public function setSlave(GenericDAO $slave) {
		Assert::isInstance($slave, ProtoDAO::class);

		return parent::setSlave($slave);
	}

	protected function sync($old, $object) {
		$changed = [];

		foreach ($this->slave->getProtoClass()->getPropertyList() as $property) {
			$getter = $property->getGetter();

			if ($property->getClassName() === null) {
				if ($old->$getter() != $object->$getter()) {
					$changed[$property->getName()] = $property;
				}

			} else {
				if ((is_object($old->$getter()) && !$old->$getter()->isEqualTo($object->$getter())) || (!$old->$getter() && $object->$getter())) {
					$changed[$property->getName()] = $property;
				}
			}
		}

		if ($changed) {
			return $this->changed($old, $object, $changed);
		}

		return false;
	}

	protected function changed($old, $object, $properties) {
		return parent::sync($old, $object);
	}
}
