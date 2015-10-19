<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Georgiy T. Kutsurua
 */
namespace Hesper\Core\Base;

/**
 * Timestamp with time zone
 * @package Hesper\Core\Base
 */
class TimestampTZ extends Timestamp {

	/**
	 * @static
	 * @return string
	 */
	protected static function getFormat() {
		return 'Y-m-d H:i:sO';
	}

	/**
	 * @return Timestamp
	 **/
	public function toTimestamp($zone = null) {
		if ($zone) {

			if (!($zone instanceof \DateTimeZone) && is_scalar($zone)) {
				$zone = new \DateTimeZone($zone);
			}

			return new static($this->toStamp(), $zone);
		}

		return parent::toTimestamp();
	}

	public static function compare(Date $left, Date $right) {
		Assert::isTrue(($left instanceof TimestampTZ && $right instanceof TimestampTZ));

		return parent::compare($left, $right);
	}
}
