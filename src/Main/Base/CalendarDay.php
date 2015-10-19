<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Base;

use Hesper\Core\Base\Date;

/**
 * Calendar day representation.
 * @package Hesper\Main\Base
 */
final class CalendarDay extends Date {

	private $selected = null;
	private $outside  = null;

	/**
	 * @return CalendarDay
	 **/
	public static function create($timestamp) {
		return new self($timestamp);
	}

	public function  __sleep() {
		$sleep = parent::__sleep();
		$sleep[] = 'selected';
		$sleep[] = 'outside';

		return $sleep;
	}

	public function isSelected() {
		return $this->selected === true;
	}

	/**
	 * @return CalendarDay
	 **/
	public function setSelected($selected) {
		$this->selected = $selected === true;

		return $this;
	}

	public function isOutside() {
		return $this->outside;
	}

	/**
	 * @return CalendarDay
	 **/
	public function setOutside($outside) {
		$this->outside = $outside === true;

		return $this;
	}
}
