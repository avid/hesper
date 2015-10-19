<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Type;

final class DoubleType extends FloatType {

	public function toColumnType() {
		return '\Hesper\Core\OSQL\DataType::double()';
	}
}
