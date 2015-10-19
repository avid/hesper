<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\UnimplementedFeatureException;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Exception\WrongStateException;
use Hesper\Main\UnifiedContainer\UnifiedContainer;
use Hesper\Main\Util\ArrayUtils;

class PrimitiveUuidIdentifierList extends PrimitiveIdentifier {

	protected $scalar = true;

	protected $value = array();

	public function setScalar($orly = false) {
		throw new UnimplementedFeatureException();
	}

	public function getTypeName()
	{
		return 'Uuid';
	}

	/**
	 * @return PrimitiveUuidIdentifierList
	 **/
	public function clean() {
		parent::clean();

		// restoring our very own default
		$this->value = array();

		return $this;
	}

	/**
	 * @return PrimitiveUuidIdentifierList
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
			if ($value->isLazy())
				return $this->import(
					array($this->name => $value->getList())
				);
			elseif (
				$value->getParentObject()->getId()
				&& ($list = $value->getList())
			) {
				return $this->import(
					array($this->name => ArrayUtils::getIdsArray($list))
				);
			} else {
				return parent::importValue(null);
			}
		}

		if (is_array($value)) {
			try {
				Assert::isUUID(current($value));

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

	public function import($scope) {
		if (!$this->className)
			throw new WrongStateException(
				"no class defined for PrimitiveUuidIdentifierList '{$this->name}'"
			);

		if (!BasePrimitive::import($scope))
			return null;

		if (!is_array($scope[$this->name]))
			return false;

		$list = array_unique($scope[$this->name]);

		$values = array();

		foreach ($list as $id) {
			if (!Assert::checkUUID($id))
				return false;

			$values[] = $id;
		}

		$objectList = $this->dao()->getListByIds($values);

		if (
			count($objectList) == count($values)
			&& !($this->min && count($values) < $this->min)
			&& !($this->max && count($values) > $this->max)
		) {
			$this->value = $objectList;
			return true;
		}

		return false;
	}

	public function exportValue() {
		if (!$this->value)
			return null;

		return ArrayUtils::getIdsArray($this->value);
	}

}
