<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Type;

/**
 * Class BinaryType
 * @package Hesper\Meta\Type
 */
final class BinaryType extends BasePropertyType {

	public function getPrimitiveName() {
		return 'binary';
	}

	public function getDeclaration() {
		return 'null';
	}

	public function toColumnType($length = null) {
		return '\Hesper\Core\OSQL\DataType::binary()';
	}

	public function isMeasurable() {
		return false;
	}
}
