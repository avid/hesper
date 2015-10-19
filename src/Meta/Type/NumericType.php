<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Type;

/**
 * Class NumericType
 * @package Hesper\Meta\Type
 */
final class NumericType extends FloatType {

	public function toColumnType() {
		return '\Hesper\Core\OSQL\DataType::numeric()';
	}
}
