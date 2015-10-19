<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Core\Base;

use Hesper\Core\Exception\UnimplementedFeatureException;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Main\Base\DateRange;

/**
 * Class IntervalUnit
 * @package Hesper\Core\Base
 */
final class IntervalUnit {

	private $name = null;

	private $months  = null;
	private $days    = null;
	private $seconds = null;

	public static function create($name) {
		return self::getInstance($name);
	}

	public function getName() {
		return $this->name;
	}

	/**
	 * @return Timestamp
	 * Emulates PostgreSQL's date_trunc() function
	 **/
	public function truncate(Date $time, $ceil = false) {
		$time = $time->toTimestamp();

		$function = $ceil ? 'ceil' : 'floor';

		if ($this->seconds) {

			if ($this->seconds < 1) {
				return $time->spawn();
			}

			$truncated = (int)($function($time->toStamp() / $this->seconds) * $this->seconds);

			return Timestamp::create($truncated);

		} elseif ($this->days) {

			$epochStartTruncated = Date::create('1970-01-05');

			$truncatedDate = Date::create($time->toDate());

			if ($ceil && $truncatedDate->toStamp() < $time->toStamp()) {
				$truncatedDate->modify('+1 day');
			}

			$difference = Date::dayDifference($epochStartTruncated, $truncatedDate);

			$truncated = (int)($function($difference / $this->days) * $this->days);

			return Timestamp::create($epochStartTruncated->spawn($truncated . ' days')
			                                             ->toStamp());

		} elseif ($this->months) {

			$monthsCount = $time->getYear() * 12 + ($time->getMonth() - 1);

			if ($ceil && (($time->getDay() - 1) + $time->getHour() + $time->getMinute() + $time->getSecond() > 0)) {
				$monthsCount += 0.1;
			} // delta

			$truncated = (int)($function($monthsCount / $this->months) * ($this->months));

			$months = $truncated % 12;

			$years = ($truncated - $months) / 12;

			Assert::isEqual($years, (int)$years);

			$years = (int)$years;

			$months = $months + 1;

			return Timestamp::create("{$years}-{$months}-01 00:00:00");
		}

		Assert::isUnreachable();
	}

	public function countInRange(DateRange $range, $overlappedBounds = true) {
		$range = $range->toTimestampRange();

		$start = $this->truncate($range->getStart(), !$overlappedBounds);

		$end = $this->truncate($range->getEnd(), $overlappedBounds);

		if ($this->seconds) {

			$result = ($end->toStamp() - $start->toStamp()) / $this->seconds;

		} elseif ($this->days) {

			$epochStartTruncated = Date::create('1970-01-05');

			$startDifference = Date::dayDifference($epochStartTruncated, Date::create($start->toDate()));

			$endDifference = Date::dayDifference($epochStartTruncated, Date::create($end->toDate()));

			$result = ($endDifference - $startDifference) / $this->days;

		} elseif ($this->months) {

			$startMonthsCount = $start->getYear() * 12 + ($start->getMonth() - 1);
			$endMonthsCount = $end->getYear() * 12 + ($end->getMonth() - 1);

			$result = ($endMonthsCount - $startMonthsCount) / $this->months;
		}

		Assert::isEqual($result, (int)$result, 'floating point mistake, arguments: ' . $this->name . ', ' . $start->toStamp() . ', ' . $end->toStamp() . ', ' . 'result: ' . var_export($result, true));

		return (int)$result;
	}

	public function compareTo(IntervalUnit $unit) {
		$monthsDiffer = $this->months - $unit->months;

		if ($monthsDiffer) {
			return $monthsDiffer;
		}

		$daysDiffer = $this->days - $unit->days;

		if ($daysDiffer) {
			return $daysDiffer;
		}

		$secondsDiffer = $this->seconds - $unit->seconds;

		if ($secondsDiffer) {
			return $secondsDiffer;
		}

		return 0;
	}

	private function __construct($name) {
		$units = self::getUnits();

		if (!isset($units[$name])) {
			throw new WrongArgumentException("know nothing about unit '$name'");
		}

		if (!$units[$name]) {
			throw new UnimplementedFeatureException('need for complex logic, see manual');
		}

		$this->name = $name;

		$this->months = $units[$name][0];
		$this->days = $units[$name][1];
		$this->seconds = $units[$name][2];

		$notNulls = 0;

		if ($this->months > 0) {
			++$notNulls;
		}

		if ($this->days > 0) {
			++$notNulls;
		}

		if ($this->seconds > 0) {
			++$notNulls;
		}

		Assert::isEqual($notNulls, 1, "broken unit '$name'");
	}

	private static function getUnits() {
		static $result = null;

		if (!$result) {
			$result = [// name			=> array(months,	days,	seconds)
				'microsecond' => [0, 0, 0.000001], 'millisecond' => [0, 0, 0.001], 'second' => [0, 0, 1], 'minute' => [0, 0, 60], 'hour' => [0, 0, 3600], 'day' => [0, 1, 0], 'week' => [0, 7, 0], 'month' => [1, 0, 0], 'year' => [12, 0, 0], 'decade' => [120, 0, 0], 'century' => [], 'millennium' => []];
		}

		return $result;
	}

	private static function getInstance($id) {
		static $instances = [];

		if (!isset($instances[$id])) {
			$instances[$id] = new self($id);
		}

		return $instances[$id];
	}
}
