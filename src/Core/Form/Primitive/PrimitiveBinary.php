<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Primitive;

/**
 * Class PrimitiveBinary
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveBinary extends FiltrablePrimitive {

	public function import($scope) {
		if (!BasePrimitive::import($scope)) {
			return null;
		}

		$this->value = (string)$scope[$this->name];

		$this->selfFilter();

		if (!empty($this->value) && is_string($this->value) && ($length = strlen($this->value)) && !($this->max && $length > $this->max) && !($this->min && $length < $this->min)) {
			return true;
		} else {
			$this->value = null;
		}

		return false;
	}
}
