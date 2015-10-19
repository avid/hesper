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
 * Class UnsignedSmallIntegerType
 * @package Hesper\Meta\Type
 */
final class UnsignedSmallIntegerType extends SmallIntegerType {

	public function getSize() {
		return 2 & LightMetaProperty::UNSIGNED_FLAG;
	}

	public function toColumnType() {
		return parent::toColumnType() . "->\n" . 'setUnsigned(true)';
	}
}
