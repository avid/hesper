<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Meta\Type;

/**
 * Class FixedLengthStringType
 * @package Hesper\Meta\Type
 */
final class FixedLengthStringType extends StringType {

	public function toColumnType($length = null) {
		return '\Hesper\Core\OSQL\DataType::char()';
	}
}
