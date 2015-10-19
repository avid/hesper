<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Type;

/**
 * Class SmallIntegerType
 * @package Hesper\Meta\Type
 */
class SmallIntegerType extends IntegerType {

	public function getSize() {
		return 2;
	}

	public function toColumnType() {
		return '\Hesper\Core\OSQL\DataType::smallint()';
	}
}
