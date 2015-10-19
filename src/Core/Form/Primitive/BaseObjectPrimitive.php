<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongArgumentException;

abstract class BaseObjectPrimitive extends BasePrimitive {

	protected $className = null;

	public function import($scope) {
		if (!BasePrimitive::import($scope)) {
			return null;
		}

		if ($scope[$this->getName()] instanceof $this->className) {
			$this->value = $scope[$this->getName()];

			return true;
		}

		try {
			$this->value = new $this->className($scope[$this->getName()]);

			return true;
		} catch (WrongArgumentException $e) {
			return false;
		}
	}

	public function setValue($value) {
		Assert::isInstance($value, $this->className);

		$this->value = $value;

		return $this;
	}

	public function setDefault($default) {
		Assert::isInstance($default, $this->className);

		$this->default = $default;

		return $this;
	}
}
