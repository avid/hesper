<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Exception\WrongArgumentException;

/**
 * Class PrimitiveHstore
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveHstore extends BasePrimitive {

	protected $formMapping = [];

	/**
	 * @return PrimitiveHstore
	 **/
	public function setFormMapping($array) {
		$this->formMapping = $array;

		return $this;
	}

	public function getFormMapping() {
		return $this->formMapping;
	}

	public function getInnerErrors() {
		if ($this->value instanceof Form) {
			return $this->value->getInnerErrors();
		}

		return [];
	}

	/**
	 * @return Form
	 **/
	public function getInnerForm() {
		return $this->value;
	}

	public function getValue() {
		if (!$this->value instanceof Form) {
			return null;
		}

		return Hstore::make($this->value->export());
	}

	/**
	 * @throws WrongArgumentException
	 * @return boolean
	 **/
	public function importValue($value) {
		if ($value === null) {
			return parent::importValue(null);
		}

		Assert::isTrue($value instanceof Hstore, 'importValue');

		if (!$this->value instanceof Form) {
			$this->value = $this->makeForm();
		}

		$this->value->import($value->getList());
		$this->imported = true;

		return $this->value->getErrors() ? false : true;
	}

	public function import($scope) {
		if (!isset($scope[$this->name])) {
			return null;
		}

		$this->rawValue = $scope[$this->name];

		if (!$this->value instanceof Form) {
			$this->value = $this->makeForm();
		}

		$this->value->import($this->rawValue);

		$this->imported = true;

		if ($this->value->getErrors()) {
			return false;
		}

		return true;
	}

	/**
	 * @return Hstore
	 **/
	public function exportValue() {
		if (!$this->value instanceof Form) {
			return null;
		}

		return !$this->value->getErrors() ? $this->value->export() : null;
	}

	/**
	 * @return Form
	 **/
	protected function makeForm() {
		$form = Form::create();

		foreach ($this->getFormMapping() as $primitive) {
			$form->add($primitive);
		}

		return $form;
	}
}
