<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Util;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Date;
use Hesper\Core\Base\IntervalUnit;
use Hesper\Core\Base\StaticFactory;
use Hesper\Core\Base\Timestamp;
use Hesper\Main\Base\DateRange;

/**
 * Utilities for playing with dates and time
 * @package Hesper\Main\Util
 */
final class DateUtils extends StaticFactory {

	public static function getAgeByBirthDate(Date $birthDate, /* Date*/ $actualDate = null) {
		if ($actualDate) {
			Assert::isInstance($actualDate, Date::class);
		} else {
			$actualDate = Date::makeToday();
		}

		$result = $actualDate->getYear() - $birthDate->getYear();

		if ($actualDate->getMonth() < $birthDate->getMonth() || ($actualDate->getMonth() == $birthDate->getMonth() && $actualDate->getDay() < $birthDate->getDay())) {
			// - Happy birthday?
			// - Happy go to hell. Not yet in this year.
			--$result;
		}

		return $result;
	}

	public static function makeFirstDayOfMonth(Date $date) {
		return Timestamp::create(mktime(0, 0, 0, $date->getMonth(), 1, $date->getYear()));
	}

	public static function makeLastDayOfMonth(Date $date) {
		return Timestamp::create(mktime(0, 0, 0, $date->getMonth() + 1, 0, $date->getYear()));
	}

	public static function makeDatesListByRange(DateRange $range, IntervalUnit $unit, $hash = true) {
		$date = $unit->truncate($range->getStart());

		if ('Date' == get_class($range->getStart())) {
			$date = Date::create($date->toStamp());
		}

		$dates = [];

		do {
			if ($hash) {
				$dates[$date->toString()] = $date;
			} else {
				$dates[] = $date;
			}

			$date = $date->spawn('+ 1' . $unit->getName());
		} while ($range->getEnd()->toStamp() >= $date->toStamp());

		return $dates;
	}

	/**
	 * @return Timestamp
	 **/
	public static function alignToSeconds(Timestamp $stamp, $seconds) {
		$rawStamp = $stamp->toStamp();

		$align = floor($rawStamp / $seconds);

		return Timestamp::create($align * $seconds);
	}
}
