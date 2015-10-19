<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Type;

use Hesper\Main\Base\LightMetaProperty;

/**
 * Class UnsignedIntegerType
 * @package Hesper\Meta\Type
 */
final class UnsignedIntegerType extends IntegerType {

	public function getSize() {
		return 4 & LightMetaProperty::UNSIGNED_FLAG;
	}

	public function toColumnType() {
		return parent::toColumnType() . "->\n" . 'setUnsigned(true)';
	}
}
