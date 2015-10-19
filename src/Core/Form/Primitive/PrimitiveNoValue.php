<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Assert;

/**
 * Class PrimitiveNoValue
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveNoValue extends BasePrimitive {

	/**
	 * @return PrimitiveNoValue
	 **/
	public function setValue($value) {
		Assert::isUnreachable('No value!');

		return $this;
	}

	public function setDefaultValue($default) {
		Assert::isUnreachable('No default value!');

		return $this;
	}

	public function setRawValue($raw) {
		Assert::isUnreachable('No raw value!');

		return $this;
	}

	public function importValue($value) {
		Assert::isUnreachable('No import value!');

		return $this;
	}

	public function import($scope) {
		if (array_key_exists($this->name, $scope) && $scope[$this->name] == null) {
			return $this->imported = true;
		}

		return null;
	}
}
