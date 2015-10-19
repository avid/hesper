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
 * Class UnsignedBigIntegerType
 * @package Hesper\Meta\Type
 */
final class UnsignedBigIntegerType extends BigIntegerType {

	public function getSize() {
		return 8 & LightMetaProperty::UNSIGNED_FLAG;
	}

	public function toColumnType() {
		return parent::toColumnType() . "->\n" . 'setUnsigned(true)';
	}
}
