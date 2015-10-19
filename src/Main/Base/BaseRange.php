<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Main\Base;

use Hesper\Core\Base\Stringable;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Numeric interval implementation and accompanying utility methods.
 * @package Hesper\Main\Base
 */
class BaseRange implements Stringable {

	protected $min = null;
	protected $max = null;

	public function __construct($min = null, $max = null) {
		$this->min = $min;
		$this->max = $max;
	}

	/**
	 * @return BaseRange
	 **/
	public static function lazyCreate($min = null, $max = null) {
		if ($min > $max) {
			self::swap($min, $max);
		}

		return new self($min, $max);
	}

	public function getMin() {
		return $this->min;
	}

	/**
	 * @throws WrongArgumentException
	 * @return BaseRange
	 **/
	public function setMin($min = null) {
		if (($this->max !== null) && $min > $this->max) {
			throw new WrongArgumentException('can not set minimal value, which is greater than maximum one');
		} else {
			$this->min = $min;
		}

		return $this;
	}

	public function getMax() {
		return $this->max;
	}

	/**
	 * @throws WrongArgumentException
	 * @return BaseRange
	 **/
	public function setMax($max = null) {
		if (($this->min !== null) && $max < $this->min) {
			throw new WrongArgumentException('can not set maximal value, which is lower than minimum one');
		} else {
			$this->max = $max;
		}

		return $this;
	}

	/// atavism wrt BC
	public function toString($from = 'от', $to = 'до') {
		$out = null;

		if ($this->min) {
			$out .= "{$from} " . $this->min;
		}

		if ($this->max) {
			$out .= " {$to} " . $this->max;
		}

		return trim($out);
	}

	/**
	 * @return BaseRange
	 **/
	public function divide($factor, $precision = null) {
		if ($this->min) {
			$this->min = round($this->min / $factor, $precision);
		}

		if ($this->max) {
			$this->max = round($this->max / $factor, $precision);
		}

		return $this;
	}

	/**
	 * @return BaseRange
	 **/
	public function multiply($multiplier) {
		if ($this->min) {
			$this->min = $this->min * $multiplier;
		}

		if ($this->max) {
			$this->max = $this->max * $multiplier;
		}

		return $this;
	}

	public function equals(BaseRange $range) {
		return ($this->min === $range->getMin() && $this->max === $range->getMax());
	}

	public function intersects(BaseRange $range) {
		return ($this->max >= $range->getMin() && $this->min <= $range->getMax());
	}

	public function isEmpty() {
		return ($this->min === null) && ($this->max === null);
	}

	public static function swap(&$a, &$b) {
		$c = $a;
		$a = $b;
		$b = $c;
	}
}
