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
 * Class IntegerType
 * @package Hesper\Meta\Type
 */
class IntegerType extends BasePropertyType {

	public function getSize() {
		return 4;
	}

	public function getPrimitiveName() {
		return 'integer';
	}

	/**
	 * @throws WrongArgumentException
	 * @return IntegerType
	 **/
	public function setDefault($default) {
		Assert::isInteger($default, "strange default value given - '{$default}'");

		$this->default = $default;

		return $this;
	}

	public function getDeclaration() {
		if ($this->hasDefault()) {
			return $this->default;
		}

		return 'null';
	}

	public function isMeasurable() {
		return false;
	}

	public function toColumnType() {
		return '\Hesper\Core\OSQL\DataType::int()';
	}
}
