<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Georgiy T. Kutsurua
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Exception\WrongStateException;
use Hesper\Main\Util\ArrayUtils;

/**
 * Class PrimitiveEnumList
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveEnumList extends PrimitiveEnum {

	protected $value = [];

	/**
	 * @return PrimitiveEnumList
	 **/
	public function clean() {
		parent::clean();

		// restoring our very own default
		$this->value = [];

		return $this;
	}

	/**
	 * @return PrimitiveEnumList
	 **/
	public function setValue(/* Enum */
		$value) {
		if ($value) {
			Assert::isArray($value);
			Assert::isInstance(current($value), Enum::class);
		}

		$this->value = $value;

		return $this;
	}

	public function importValue($value) {
		if (is_array($value)) {
			try {
				Assert::isInteger(current($value));

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
			if (!Assert::checkInteger($id)) {
				return false;
			}

			$values[] = $id;
		}

		$objectList = [];

		foreach ($values as $value) {
			$className = $this->className;
			$objectList[] = new $className($value);
		}

		if (count($objectList) == count($values)) {
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
}
