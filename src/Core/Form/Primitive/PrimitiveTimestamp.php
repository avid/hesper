<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Timestamp;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Class PrimitiveTimestamp
 * @package Hesper\Core\Form\Primitive
 */
class PrimitiveTimestamp extends PrimitiveDate {

	const HOURS   = 'hrs';
	const MINUTES = 'min';
	const SECONDS = 'sec';

	public function importMarried($scope) {
		if (BasePrimitive::import($scope) && isset($scope[$this->name][self::DAY], $scope[$this->name][self::MONTH], $scope[$this->name][self::YEAR]) && is_array($scope[$this->name])) {
			if ($this->isEmpty($scope)) {
				return !$this->isRequired();
			}

			$hours = $minutes = $seconds = 0;

			if (isset($scope[$this->name][self::HOURS])) {
				$hours = (int)$scope[$this->name][self::HOURS];
			}

			if (isset($scope[$this->name][self::MINUTES])) {
				$minutes = (int)$scope[$this->name][self::MINUTES];
			}

			if (isset($scope[$this->name][self::SECONDS])) {
				$seconds = (int)$scope[$this->name][self::SECONDS];
			}

			$year = (int)$scope[$this->name][self::YEAR];
			$month = (int)$scope[$this->name][self::MONTH];
			$day = (int)$scope[$this->name][self::DAY];

			if (!checkdate($month, $day, $year)) {
				return false;
			}

			try {
				$stamp = new Timestamp($year . '-' . $month . '-' . $day . ' ' . $hours . ':' . $minutes . ':' . $seconds);
			} catch (WrongArgumentException $e) {
				// fsck wrong stamps
				return false;
			}

			if ($this->checkRanges($stamp)) {
				$this->value = $stamp;

				return true;
			}
		}

		return false;
	}

	protected function getObjectName() {
		return '\Hesper\Core\Base\Timestamp';
	}

	public function exportValue() {
		$parent = parent::exportValue();

		if (is_array($parent)) {

			if ($this->value) {
				$parent[static::HOURS] = $this->value->getHour();
				$parent[static::MINUTES] = $this->value->getMinute();
				$parent[static::SECONDS] = $this->value->getSecond();

			} else {

				$parent[static::HOURS] = null;
				$parent[static::MINUTES] = null;
				$parent[static::SECONDS] = null;
			}
		}

		return $parent;
	}
}
