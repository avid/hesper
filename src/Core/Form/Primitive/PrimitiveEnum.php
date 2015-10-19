<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Georgiy T. Kutsurua
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Enum;
use Hesper\Core\Exception\MissingElementException;
use Hesper\Core\Exception\UnsupportedMethodException;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Exception\WrongStateException;
use Hesper\Main\Util\ClassUtils;

/**
 * Class PrimitiveEnum
 * @package Hesper\Core\Form\Primitive
 */
class PrimitiveEnum extends IdentifiablePrimitive implements ListedPrimitive {

	public function getList() {
		if ($this->value) {
			return ClassUtils::callStaticMethod(get_class($this->value) . '::getList');
		} elseif ($this->default) {
			return ClassUtils::callStaticMethod(get_class($this->default) . '::getList');
		} else {
			$object = new $this->className(ClassUtils::callStaticMethod($this->className . '::getAnyId'));

			return $object->getObjectList();
		}
	}

	/**
	 * @throws WrongArgumentException
	 * @return PrimitiveEnum
	 **/
	public function of($class) {
		$className = $this->guessClassName($class);

		Assert::classExists($className);

		Assert::isInstance($className, Enum::class);

		$this->className = $className;

		return $this;
	}

	public function importValue(/* Identifiable */
		$value) {
		if ($value) {
			Assert::isInstance($value, $this->className);
//			Assert::isEqual(get_class($value), $this->className);
		} else {
			return parent::importValue(null);
		}

		return $this->import([$this->getName() => $value->getId()]);
	}

	public function import($scope) {
		$result = parent::import($scope);

		if ($result === true) {
			try {
				$this->value = $this->makeEnumById($this->value);
			} catch (MissingElementException $e) {
				$this->value = null;

				return false;
			}

			return true;
		}

		return $result;
	}

	/**
	 * @param $list
	 *
	 * @throws UnsupportedMethodException
	 */
	public function setList($list) {
		throw new UnsupportedMethodException('you cannot set list here, it is impossible, because list getted from enum classes');
	}

	/**
	 * @return null|string
	 */
	public function getChoiceValue() {
		if (($value = $this->getValue()) && $value instanceof Enum) {
			return $value->getName();
		}

		return null;
	}


	/**
	 * @return Enum|mixed|null
	 */
	public function getActualChoiceValue() {
		if (!$this->getChoiceValue() && $this->getDefault()) {
			return $this->getDefault()
			            ->getName();
		}

		return null;
	}

	/**
	 * @param $id
	 *
	 * @return Enum|mixed
	 */
	protected function makeEnumById($id) {
		if (!$this->className) {
			throw new WrongStateException("no class defined for PrimitiveEnum '{$this->name}'");
		}

		return new $this->className($id);
	}
}
