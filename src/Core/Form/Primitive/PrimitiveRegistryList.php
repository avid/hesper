<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Registry;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Exception\WrongStateException;
use Hesper\Main\Util\ArrayUtils;

/**
 * @ingroup Primitives
 **/
final class PrimitiveRegistryList extends PrimitiveRegistry
{
	protected $value = array();

	/**
	 * @return PrimitiveRegistryList
	 **/
	public function clean()
	{
		parent::clean();

		// restoring our very own default
		$this->value = array();

		return $this;
	}

	/**
	 * @return PrimitiveRegistryList
	 **/
	public function setValue(/* Registry */ $value)
	{
		if ($value) {
			Assert::isArray($value);
			Assert::isInstance(current($value), Registry::class);
		}

		$this->value = $value;

		return $this;
	}

	public function importValue($value)
	{
		if (is_array($value)) {
			try {
				Assert::isScalar(current($value));

				return $this->import(
					array($this->name => $value)
				);
			} catch (WrongArgumentException $e) {
				return $this->import(
					array($this->name => ArrayUtils::getIdsArray($value))
				);
			}
		}

		return parent::importValue($value);
	}

	public function import($scope)
	{
		if (!$this->className)
			throw new WrongStateException(
				"no class defined for PrimitiveIdentifierList '{$this->name}'"
			);

		if (!BasePrimitive::import($scope))
			return null;

		if (!is_array($scope[$this->name]))
			return false;

		$list = array_unique($scope[$this->name]);

		$values = array();

		foreach ($list as $id) {
			if (!Assert::checkScalar($id))
				return false;

			$values[] = $id;
		}

		$objectList = array();

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

	public function exportValue()
	{
		if (!$this->value)
			return null;

		return ArrayUtils::getIdsArray($this->value);
	}
}