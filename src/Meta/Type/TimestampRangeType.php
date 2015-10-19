<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Type;

use Hesper\Main\Base\TimestampRange;

/**
 * Class TimestampRangeType
 * @package Hesper\Meta\Type
 */
class TimestampRangeType extends DateRangeType {

	public function toPrimitive() {
		return 'Primitive::timestampRange';
	}

	public function getFullClass() {
		return TimestampRange::class;
	}

}
