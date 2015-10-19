<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Primitive;

/**
 * Parent of every Primitive.
 * @package Hesper\Core\Form\Primitive
 */
abstract class BasePrimitive {

	protected $name    = null;
	protected $default = null;
	protected $value   = null;

	protected $required = false;
	protected $imported = false;

	protected $raw = null;

	protected $customError = null;

	public function __construct($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	/**
	 * @return BasePrimitive
	 **/
	public function setName($name) {
		$this->name = $name;

		return $this;
	}

	public function getDefault() {
		return $this->default;
	}

	/**
	 * @return BasePrimitive
	 **/
	public function setDefault($default) {
		$this->default = $default;

		return $this;
	}

	public function getValue() {
		return $this->value;
	}

	public function getRawValue() {
		return $this->raw;
	}

	public function getValueOrDefault() {
		if ($this->value !== null) {
			return $this->value;
		}

		return $this->default;
	}

	/**
	 * @deprecated since version 1.0
	 * @see        getSafeValue, getValueOrDefault
	 */
	public function getActualValue() {
		if ($this->value !== null) {
			return $this->value;
		} elseif ($this->imported) {
			return $this->raw;
		}

		return $this->default;
	}

	public function getSafeValue() {
		if ($this->imported) {
			return $this->value;
		}

		return $this->default;
	}

	/**
	 * @return BasePrimitive
	 **/
	public function setValue($value) {
		$this->value = $value;

		return $this;
	}

	/**
	 * @return BasePrimitive
	 **/
	public function dropValue() {
		$this->value = null;

		return $this;
	}

	/**
	 * @return BasePrimitive
	 * usually, you should not use this method
	 **/
	public function setRawValue($raw) {
		$this->raw = $raw;

		return $this;
	}

	public function isRequired() {
		return $this->required;
	}

	/**
	 * @return BasePrimitive
	 **/
	public function setRequired($really = false) {
		$this->required = (true === $really ? true : false);

		return $this;
	}

	/**
	 * @return BasePrimitive
	 **/
	public function required() {
		$this->required = true;

		return $this;
	}

	/**
	 * @return BasePrimitive
	 **/
	public function optional() {
		$this->required = false;

		return $this;
	}

	public function isImported() {
		return $this->imported;
	}

	/**
	 * @return BasePrimitive
	 **/
	public function clean() {
		$this->raw = null;
		$this->value = null;
		$this->imported = false;

		return $this;
	}

	public function importValue($value) {
		return $this->import([$this->getName() => $value]);
	}

	public function exportValue() {
		return $this->value;
	}

	public function getCustomError() {
		return $this->customError;
	}

	public function import($scope) {
		if (!empty($scope[$this->name]) || (isset($scope[$this->name]) && $scope[$this->name] !== '')) {
			$this->raw = $scope[$this->name];

			return $this->imported = true;
		}

		$this->clean();

		return null;
	}
}
