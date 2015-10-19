<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Type;

use Hesper\Core\Base\Time;

/**
 * Class TimeType
 * @package Hesper\Meta\Type
 */
final class TimeType extends ObjectType {

	public function getPrimitiveName() {
		return 'time';
	}

	public function getFullClass() {
		return Time::class;
	}

	public function isGeneric() {
		return true;
	}

	public function toColumnType() {
		return '\Hesper\Core\OSQL\DataType::time()';
	}
}
