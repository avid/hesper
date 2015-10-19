<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov, Anton E. Lebedevich
 */
namespace Hesper\Core\Form\Primitive;

/**
 * Class PrimitiveBoolean
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveBoolean extends BasePrimitive {

	public function import($scope) {
		if (isset($scope[$this->name])) {
			$this->value = true;
		} else {
			$this->value = false;
		}

		return $this->imported = true;
	}

	public function importValue($value) {
		if (false === $value || null === $value) {
			$this->value = false;
		} else {
			$this->value = true;
		}

		return $this->imported = true;
	}

	public function isImported() {
		return ($this->imported && $this->value);
	}
}
