<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov, Igor V. Gulyaev
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Time;
use Hesper\Core\Exception\UnimplementedFeatureException;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Class TimeList
 * @package Hesper\Core\Form\Primitive
 */
final class TimeList extends BasePrimitive {

	protected $value = [];

	/**
	 * @return TimeList
	 **/
	public function clean() {
		parent::clean();

		$this->value = [];

		return $this;
	}

	public function import($scope) {
		if (empty($scope[$this->name]) || !is_array($scope[$this->name])) {
			return null;
		}

		$this->raw = $scope[$this->name];
		$this->imported = true;

		$array = $scope[$this->name];
		$list = [];

		foreach ($array as $string) {
			$timeList = self::stringToTimeList($string);

			if ($timeList) {
				$list[] = $timeList;
			}
		}

		$this->value = $list;

		return ($this->value !== []);
	}

	public function getValueOrDefault() {
		if (is_array($this->value) && $this->value[0]) {
			return $this->value;
		}

		return [$this->default];
	}

	/**
	 * @deprecated deprecated since version 1.0
	 * @see        getSafeValue, getValueOrDefault
	 * @return type
	 */
	public function getActualValue() {
		if (is_array($this->value) && $this->value[0]) {
			return $this->value;
		} elseif (is_array($this->raw) && $this->raw[0]) {
			return $this->raw;
		}

		return [$this->default];
	}

	public static function stringToTimeList($string) {
		$list = [];

		$times = split("([,; \n]+)", $string);

		for ($i = 0, $size = count($times); $i < $size; ++$i) {
			$time = mb_ereg_replace('[^0-9:]', ':', $times[$i]);

			try {
				$list[] = Time::create($time);
			} catch (WrongArgumentException $e) {/* ignore */
			}
		}

		return $list;
	}

	public function exportValue() {
		throw new UnimplementedFeatureException();
	}
}
