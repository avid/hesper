<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov, Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Enumeration;
use Hesper\Core\Exception\MissingElementException;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Exception\WrongStateException;

/**
 * Class PrimitiveEnumeration
 * @package Hesper\Core\Form\Primitive
 */
class PrimitiveEnumeration extends IdentifiablePrimitive {

	public function getList() {
		if ($this->value) {
			return $this->value->getObjectList();
		} elseif ($this->default) {
			return $this->default->getObjectList();
		} else {
			$object = new $this->className(call_user_func([$this->className, 'getAnyId']));

			return $object->getObjectList();
		}
	}

	/**
	 * @throws WrongArgumentException
	 * @return PrimitiveEnumeration
	 **/
	public function of($class) {
		$className = $this->guessClassName($class);

		Assert::classExists($className);

		Assert::isInstance($className, Enumeration::class);

		$this->className = $className;

		return $this;
	}

	public function importValue(/* Identifiable */
		$value) {
		if ($value) {
			Assert::isEqual(get_class($value), $this->className);
		} else {
			return parent::importValue(null);
		}

		return $this->import([$this->getName() => $value->getId()]);
	}

	public function import($scope) {
		if (!$this->className) {
			throw new WrongStateException("no class defined for PrimitiveEnumeration '{$this->name}'");
		}

		$result = parent::import($scope);

		if ($result === true) {
			try {
				$this->value = new $this->className($this->value);
			} catch (MissingElementException $e) {
				$this->value = null;

				return false;
			}

			return true;
		}

		return $result;
	}
}
