<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\UnimplementedFeatureException;
use Hesper\Core\Exception\WrongStateException;

/**
 * Class PrimitiveMultiList
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveMultiList extends PrimitiveList {

	private $selected = [];

	public function getChoiceValue() {
		return $this->selected;
	}

	public function getActualChoiceValue() {
		if ($this->value !== null) {
			return $this->selected;
		} elseif ($this->default) {
			$out = [];

			foreach ($this->default as $index) {
				$out[] = $this->list[$index];
			}

			return $out;
		}

		return [];
	}

	/**
	 * @return PrimitiveMultiList
	 **/
	public function setDefault($default) {
		Assert::isArray($default);

		foreach ($default as $index) {
			Assert::isTrue(array_key_exists($index, $this->list));
		}

		return parent::setDefault($default);
	}

	public function import($scope) {
		if (!BasePrimitive::import($scope)) {
			return null;
		}

		if (!$this->list) {
			throw new WrongStateException('list to check is not set; ' . 'use PrimitiveArray in case it is intentional');
		}

		if (is_array($scope[$this->name])) {
			$values = [];

			foreach ($scope[$this->name] as $value) {
				if (isset($this->list[$value])) {
					$values[] = $value;
					$this->selected[$value] = $this->list[$value];
				}
			}

			if (count($values)) {
				$this->value = $values;

				return true;
			}
		} elseif (!empty($scope[$this->name])) {
			$this->value = [$scope[$this->name]];

			return true;
		}

		return false;
	}

	/**
	 * @return PrimitiveMultiList
	 **/
	public function clean() {
		$this->selected = [];

		return parent::clean();
	}

	public function exportValue() {
		throw new UnimplementedFeatureException();
	}
}
