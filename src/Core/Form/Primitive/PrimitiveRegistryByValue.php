<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Exception\MissingElementException;
use Hesper\Core\Exception\WrongStateException;
use Hesper\Main\Util\ClassUtils;

/**
 * @ingroup Primitives
 **/
final class PrimitiveRegistryByValue extends PrimitiveRegistry
{
	public function import($scope)
	{
		if (!$this->className)
			throw new WrongStateException(
				"no class defined for PrimitiveRegistry '{$this->name}'"
			);

		if (isset($scope[$this->name])) {
			$scopedValue = urldecode($scope[$this->name]);

			$names = ClassUtils::callStaticMethod($this->className.'::getNameList');

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
