<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry E. Demidov
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Assert;

/**
 * Class PrimitiveTernary
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveTernary extends BasePrimitive {

	private $falseValue = 0;
	private $trueValue  = 1;

	/**
	 * @return PrimitiveTernary
	 **/
	public function setTrueValue($trueValue) {
		$this->trueValue = $trueValue;

		return $this;
	}

	/**
	 * @return PrimitiveTernary
	 **/
	public function setFalseValue($falseValue) {
		$this->falseValue = $falseValue;

		return $this;
	}

	public function import($scope) {
		if (isset($scope[$this->name])) {
			if ($this->trueValue == $scope[$this->name]) {
				$this->value = true;
			} elseif ($this->falseValue == $scope[$this->name]) {
				$this->value = false;
			} else {
				return false;
			}
		} else {
			$this->clean();

			return null;
		}

		$this->raw = $scope[$this->name];

		return $this->imported = true;
	}

	public function importValue($value) {
		Assert::isTernaryBase($value, 'only ternary based accepted');

		$this->value = $value;

		return $this->imported = true;
	}
}
