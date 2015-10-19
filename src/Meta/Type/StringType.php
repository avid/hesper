<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Type;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Class StringType
 * @package Hesper\Meta\Type
 */
class StringType extends BasePropertyType {

	public function getPrimitiveName() {
		return 'string';
	}

	/**
	 * @throws WrongArgumentException
	 * @return StringType
	 **/
	public function setDefault($default) {
		Assert::isString($default, "strange default value given - '{$default}'");

		$this->default = $default;

		return $this;
	}

	public function getDeclaration() {
		if ($this->hasDefault()) {
			return "'{$this->default}'";
		}

		return 'null';
	}

	public function isMeasurable() {
		return true;
	}

	public function toColumnType($length = null) {
		return $length ? '\Hesper\Core\OSQL\DataType::varchar()' : '\Hesper\Core\OSQL\DataType::text()';
	}
}
