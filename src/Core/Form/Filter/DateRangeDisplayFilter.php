<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Igor V. Gulyaev
 */
namespace Hesper\Core\Form\Filter;

use Hesper\Core\Base\Singleton;
use Hesper\Main\Base\DateRange;

/**
 * @ingroup Filters
 **/
final class DateRangeDisplayFilter extends BaseFilter {

	/**
	 * @return DateRangeDisplayFilter
	 **/
	public static function me() {
		return Singleton::getInstance(self::class);
	}

	public function apply($value) {
		$result = null;

		if ($value instanceof DateRange) {
			if ($value->getStart()) {
				$result = $value->getStart()->toDate('.');
			}

			$result .= ' - ';

			if ($value->getEnd()) {
				$result .= $value->getEnd()->toDate('.');
			}

			return $result;
		} else {
			return $value;
		}
	}
}
