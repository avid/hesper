<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov, Anton E. Lebedevich
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Assert;
use Hesper\Main\UnifiedContainer\UnifiedContainer;

/**
 * @ingroup Primitives
 **/
class PrimitiveArray extends FiltrablePrimitive {

	/**
	 * Fetching strategy for incoming containers:
	 * null - do nothing;
	 * true - lazy fetch;
	 * false - full fetch.
	 **/
	private $fetchMode = null;

	/**
	 * @return PrimitiveArray
	 **/
	public function setFetchMode($ternary) {
		Assert::isTernaryBase($ternary);

		$this->fetchMode = $ternary;

		return $this;
	}

	public function import($scope) {
		if (!BasePrimitive::import($scope)) {
			return null;
		}

		$this->value = $scope[$this->name];

		$this->selfFilter();

		if (is_array($this->value) && !($this->min && count($this->value) < $this->min) && !($this->max && count($this->value) > $this->max)) {
			return true;
		} else {
			$this->value = null;
		}

		return false;
	}

	public function importValue($value) {
		if ($value instanceof UnifiedContainer) {
			if (($this->fetchMode !== null) && ($value->getParentObject()
			                                          ->getId())
			) {
				if ($value->isLazy() === $this->fetchMode) {
					$value = $value->getList();
				} else {
					$className = get_class($value);

					$containter = new $className($value->getParentObject(), $this->fetchMode);

					$value = $containter->getList();
				}
			} elseif (!$value->isFetched()) {
				return null;
			}
		}

		if (is_array($value)) {
			return $this->import([$this->getName() => $value]);
		}

		return false;
	}
}
