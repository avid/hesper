<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin, Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Exception\WrongStateException;
use Hesper\Main\Util\ArrayUtils;

/**
 * Class PrimitiveIdentifierList
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveIdentifierList extends PrimitiveIdentifier {

	protected $value       = [];
	private   $ignoreEmpty = false;
	private   $ignoreWrong = false;

	/**
	 * @return PrimitiveIdentifierList
	 **/
	public function clean() {
		parent::clean();

		// restoring our very own default
		$this->value = [];

		return $this;
	}

	/**
	 * @return PrimitiveIdentifierList
	 **/
	public function setValue($value) {
		if ($value) {
			Assert::isArray($value);
			Assert::isInstance(current($value), $this->className);
		}

		$this->value = $value;

		return $this;
	}

	public function importValue($value) {
		if ($value instanceof UnifiedContainer) {
			if ($value->isLazy()) {
				return $this->import([$this->name => $value->getList()]);
			} elseif ($value->getParentObject()
			                ->getId() && ($list = $value->getList())
			) {
				return $this->import([$this->name => ArrayUtils::getIdsArray($list)]);
			} else {
				return parent::importValue(null);
			}
		}

		if (is_array($value)) {
			try {
				if ($this->scalar) {
					Assert::isScalar(current($value));
				} else {
					Assert::isInteger(current($value));
				}

				return $this->import([$this->name => $value]);
			} catch (WrongArgumentException $e) {
				return $this->import([$this->name => ArrayUtils::getIdsArray($value)]);
			}
		}

		return parent::importValue($value);
	}

	public function import($scope) {
		if (!$this->className) {
			throw new WrongStateException("no class defined for PrimitiveIdentifierList '{$this->name}'");
		}

		if (!BasePrimitive::import($scope)) {
			return null;
		}

		if (!is_array($scope[$this->name])) {
			return false;
		}

		$list = array_unique($scope[$this->name]);

		$values = [];

		foreach ($list as $id) {
			if ((string)$id == "" && $this->isIgnoreEmpty()) {
				continue;
			}

			if (($this->scalar && !Assert::checkScalar($id)) || (!$this->scalar && !Assert::checkInteger($id))) {
				if (!$this->isIgnoreWrong()) {
					return false;
				} else {
					continue;
				} //just skip it
			}

			$values[] = $id;
		}

		$objectList = $this->dao()
		                   ->getListByIds($values);

		if (((count($objectList) == count($values)) || $this->isIgnoreWrong()) && !($this->min && count($values) < $this->min) && !($this->max && count($values) > $this->max)) {
			$this->value = $objectList;

			return true;
		}

		return false;
	}

	public function exportValue() {
		if (!$this->value) {
			return null;
		}

		return ArrayUtils::getIdsArray($this->value);
	}

	public function setIgnoreEmpty($orly = true) {
		$this->ignoreEmpty = ($orly === true);

		return $this;
	}

	public function isIgnoreEmpty() {
		return $this->ignoreEmpty;
	}

	public function setIgnoreWrong($orly = true) {
		$this->ignoreWrong = ($orly === true);

		return $this;
	}

	public function isIgnoreWrong() {
		return $this->ignoreWrong;
	}
}
