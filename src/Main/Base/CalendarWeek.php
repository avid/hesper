<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Base;

/**
 * Calendar week representation.
 * @package Hesper\Main\Base
 */
final class CalendarWeek {

	private $days = [];

	/**
	 * @return CalendarWeek
	 **/
	public static function create() {
		return new self;
	}

	public function getDays() {
		return $this->days;
	}

	/**
	 * @return CalendarWeek
	 **/
	public function addDay(CalendarDay $day) {
		$this->days[$day->toDate()] = $day;

		return $this;
	}
}
