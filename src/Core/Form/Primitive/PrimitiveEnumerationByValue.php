<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Exception\MissingElementException;
use Hesper\Core\Exception\WrongStateException;
use Hesper\Main\Util\ClassUtils;

/**
 * Class PrimitiveEnumerationByValue
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveEnumerationByValue extends PrimitiveEnumeration {

	public function import($scope) {
		if (!$this->className) {
			throw new WrongStateException("no class defined for PrimitiveEnumeration '{$this->name}'");
		}

		if (isset($scope[$this->name])) {
			$scopedValue = urldecode($scope[$this->name]);

			$anyId = ClassUtils::callStaticMethod($this->className . '::getAnyId');

			$object = new $this->className($anyId);

			$names = $object->getNameList();

			foreach ($names as $key => $value) {
				if ($value == $scopedValue) {
					try {
						$this->value = new $this->className($key);
					} catch (MissingElementException $e) {
						$this->value = null;

						return false;
					}

					return true;
				}
			}

			return false;
		}

		return null;
	}
}
