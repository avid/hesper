<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Primitive;

/**
 * Class RangedPrimitive
 * @package Hesper\Core\Form\Primitive
 */
abstract class RangedPrimitive extends BasePrimitive {

	protected $min = null;
	protected $max = null;

	public function getMin() {
		return $this->min;
	}

	/**
	 * @return RangedPrimitive
	 **/
	public function setMin($min) {
		$this->min = $min;

		return $this;
	}

	public function getMax() {
		return $this->max;
	}

	/**
	 * @return RangedPrimitive
	 **/
	public function setMax($max) {
		$this->max = $max;

		return $this;
	}
}
