<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Type;

/**
 * Class BigIntegerType
 * @package Hesper\Meta\Type
 */
class BigIntegerType extends IntegerType {

	public function getSize() {
		return 8;
	}

	public function toColumnType() {
		return '\Hesper\Core\OSQL\DataType::bigint()';
	}
}
