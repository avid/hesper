<?php
/**
 * @project binatex
 * @author  Alex Gorbylev
 */

namespace Hesper\Meta\Type;

class UuidType extends BasePropertyType {

	public function getPrimitiveName() {
		return 'uuid';
	}

	public function getDeclaration() {
		if ($this->hasDefault()) {
			return "'{$this->default}'";
		}
		return 'null';
	}

	public function isMeasurable() {
		return false;
	}

	public function toColumnType() {
		return '\Hesper\Core\OSQL\DataType::uuid()';
	}

}