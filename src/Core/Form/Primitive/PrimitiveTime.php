<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Time;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Class PrimitiveTime
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveTime extends ComplexPrimitive {

	const HOURS   = PrimitiveTimestamp::HOURS;
	const MINUTES = PrimitiveTimestamp::MINUTES;
	const SECONDS = PrimitiveTimestamp::SECONDS;

	/**
	 * @throws WrongArgumentException
	 * @return PrimitiveTime
	 **/
	public function setValue(/* Time */
		$time) {
		Assert::isTrue($time instanceof Time);

		$this->value = $time;

		return $this;
	}

	/**
	 * @throws WrongArgumentException
	 * @return PrimitiveTime
	 **/
	public function setMin(/* Time */
		$time) {
		Assert::isTrue($time instanceof Time);

		$this->min = $time;

		return $this;
	}

	/**
	 * @throws WrongArgumentException
	 * @return PrimitiveTime
	 **/
	public function setMax(/* Time */
		$time) {
		Assert::isTrue($time instanceof Time);

		$this->max = $time;

		return $this;
	}

	/**
	 * @throws WrongArgumentException
	 * @return PrimitiveTime
	 **/
	public function setDefault(/* Time */
		$time) {
		Assert::isTrue($time instanceof Time);

		$this->default = $time;

		return $this;
	}

	public function importSingle($scope) {
		if (!BasePrimitive::import($scope)) {
			return null;
		}

		try {
			$time = new Time($scope[$this->name]);
		} catch (WrongArgumentException $e) {
			return false;
		}

		if ($this->checkLimits($time)) {
			$this->value = $time;

			return true;
		}

		return false;
	}

	public function isEmpty($scope) {
		if ($this->getState()
		         ->isFalse()
		) {
			return $this->isMarriedEmpty($scope);
		}

		return empty($scope[$this->name]);
	}

	public function importMarried($scope) {
		if (BasePrimitive::import($scope) && is_array($scope[$this->name]) && !$this->isMarriedEmpty($scope)) {
			$this->raw = $scope[$this->name];
			$this->imported = true;

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

			try {
				$time = new Time($hours . ':' . $minutes . ':' . $seconds);
			} catch (WrongArgumentException $e) {
				return false;
			}

			if ($this->checkLimits($time)) {
				$this->value = $time;

				return true;
			}
		}

		return false;
	}

	public function import($scope) {
		if ($this->isEmpty($scope)) {
			$this->value = null;
			$this->raw = null;

			return null;
		}

		return parent::import($scope);
	}

	public function importValue($value) {
		if ($value) {
			Assert::isTrue($value instanceof Time);
		} else {
			return parent::importValue(null);
		}

		return $this->importSingle([$this->getName() => $value->toFullString()]);
	}

	private function isMarriedEmpty($scope) {
		return empty($scope[$this->name][self::HOURS]) || empty($scope[$this->name][self::MINUTES]) || empty($scope[$this->name][self::SECONDS]);
	}

	private function checkLimits(Time $time) {
		return !($this->min && $this->min->toSeconds() > $time->toSeconds()) && !($this->max && $this->max->toSeconds() < $time->toSeconds());
	}
}
