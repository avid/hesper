<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Exception\WrongArgumentException;

/**
 * Class PrimitiveNumber
 * @package Hesper\Core\Form\Primitive
 */
abstract class PrimitiveNumber extends FiltrablePrimitive {

	abstract protected function checkNumber($number);

	abstract protected function castNumber($number);

	public function import($scope) {
		if (!BasePrimitive::import($scope)) {
			return null;
		}

		try {
			$this->checkNumber($scope[$this->name]);
		} catch (WrongArgumentException $e) {
			return false;
		}

		$this->value = $this->castNumber($scope[$this->name]);

		$this->selfFilter();

		if (!(null !== $this->min && $this->value < $this->min) && !(null !== $this->max && $this->value > $this->max)) {
			return true;
		} else {
			$this->value = null;
		}

		return false;
	}
}
