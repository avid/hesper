<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\ClassNotFoundException;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Main\Util\ClassUtils;

/**
 * Class PrimitiveClass
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveClass extends PrimitiveString {

	private $ofClassName = null;

	public function import($scope) {
		if (!($result = parent::import($scope))) {
			return $result;
		}

		if (!ClassUtils::isClassName($scope[$this->name]) || !$this->classExists($scope[$this->name]) || ($this->ofClassName && !ClassUtils::isInstanceOf($scope[$this->name], $this->ofClassName))) {
			$this->value = null;

			return false;
		}

		return true;
	}

	/**
	 * @throws WrongArgumentException
	 * @return PrimitiveIdentifier
	 **/
	public function of($class) {
		$className = $this->guessClassName($class);

		Assert::isTrue(class_exists($className, true) || interface_exists($className, true), "knows nothing about '{$className}' class/interface");

		$this->ofClassName = $className;

		return $this;
	}

	private function classExists($name) {
		try {
			return class_exists($name, true);
		} catch (ClassNotFoundException $e) {
			return false;
		}
	}

	private function guessClassName($class) {
		if (is_string($class)) {
			return $class;
		} elseif (is_object($class)) {
			return get_class($class);
		}

		throw new WrongArgumentException('strange class given - ' . $class);
	}
}
