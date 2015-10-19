<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Ternary;
use Hesper\Core\Exception\UnimplementedFeatureException;

/**
 * Basis for primitives which can be scattered across import scope.
 * @package Hesper\Core\Form\Primitive
 */
abstract class ComplexPrimitive extends RangedPrimitive {

	private $single = null;    // single, not single or fsck it

	public function __construct($name) {
		$this->single = new Ternary(null);
		parent::__construct($name);
	}

	/**
	 * @return Ternary
	 **/
	public function getState() {
		return $this->single;
	}

	/**
	 * @return ComplexPrimitive
	 **/
	public function setState(Ternary $ternary) {
		$this->single->setValue($ternary->getValue());

		return $this;
	}

	/**
	 * @return ComplexPrimitive
	 **/
	public function setSingle() {
		$this->single->setTrue();

		return $this;
	}

	/**
	 * @return ComplexPrimitive
	 **/
	public function setComplex() {
		$this->single->setFalse();

		return $this;
	}

	/**
	 * @return ComplexPrimitive
	 **/
	public function setAnyState() {
		$this->single->setNull();

		return $this;
	}

	// implement me, child :-)
	abstract public function importSingle($scope);

	abstract public function importMarried($scope);

	public function import($scope) {
		if (!BasePrimitive::import($scope)) {
			return null;
		}

		if ($this->single->isTrue()) {
			return $this->importSingle($scope);
		} elseif ($this->single->isFalse()) {
			return $this->importMarried($scope);
		} else {
			if (!$this->importMarried($scope)) {
				return $this->importSingle($scope);
			}

			return true;
		}
	}

	public function exportValue() {
		throw new UnimplementedFeatureException();
	}
}
