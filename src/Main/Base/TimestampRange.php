<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Base;

use Hesper\Core\Base\Timestamp;

/**
 * Class TimestampRange
 * @package Hesper\Main\Base
 * @see     Timestamp
 * @see     DateRange
 */
class TimestampRange extends DateRange {

	/**
	 * @return TimestampRange
	 **/
	public static function create($start = null, $end = null) {
		return new self($start, $end);
	}

	public function getStartStamp() // null if start is null
	{
		if ($start = $this->getStart()) {
			return $start->toStamp();
		}

		return null;
	}

	public function getEndStamp() // null if end is null
	{
		if ($end = $this->getEnd()) {
			return $end->toStamp();
		}

		return null;
	}

	/**
	 * @return TimestampRange
	 **/
	public function toTimestampRange() {
		return $this;
	}

	protected function getObjectName() {
		return Timestamp::class;
	}
}
