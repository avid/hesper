<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Type;

use Hesper\Core\Base\Date;

/**
 * Class DateType
 * @package Hesper\Meta\Type
 */
class DateType extends ObjectType {

	public function getPrimitiveName() {
		return 'date';
	}

	public function getFullClass() {
		return Date::class;
	}

	public function isGeneric() {
		return true;
	}

	public function toColumnType() {
		return '\Hesper\Core\OSQL\DataType::date()';
	}
}
