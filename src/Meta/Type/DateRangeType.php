<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Type;

use Hesper\Main\Base\DateRange;

/**
 * Class DateRangeType
 * @package Hesper\Meta\Type
 */
class DateRangeType extends RangeType {

	public function getPrimitiveName() {
		return 'dateRange';
	}

	public function getFullClass() {
		return DateRange::class;
	}

}
