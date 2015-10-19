<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Assert;

/**
 * Class PrimitiveList
 * @package Hesper\Core\Form\Primitive
 */
class PrimitiveList extends BasePrimitive implements ListedPrimitive {

	protected $list = [];

	public function getChoiceValue() {
		if ($this->value !== null) {
			return $this->list[$this->value];
		}

		return null;
	}

	public function getActualChoiceValue() {
		if ($this->value !== null) {
			return $this->list[$this->value];
		}

		return $this->list[$this->default];
	}

	/**
	 * @return PrimitiveList
	 **/
	public function setDefault($default) {
		Assert::isTrue($this->list && array_key_exists($default, $this->list),

			'can not find element with such index');

		return parent::setDefault($default);
	}

	public function getList() {
		return $this->list;
	}

	/**
	 * @return PrimitiveList
	 **/
	public function setList($list) {
		$this->list = $list;

		return $this;
	}

	public function import($scope) {
		if (!parent::import($scope)) {
			return null;
		}

		if ((is_string($scope[$this->name]) || is_integer($scope[$this->name])) && array_key_exists($scope[$this->name], $this->list)) {
			$this->value = $scope[$this->name];

			return true;
		}

		return false;
	}
}
