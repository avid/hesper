<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Base;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Integer's interval implementation and accompanying utility methods.
 * @package Hesper\Main\Base
 */
class Range extends BaseRange {

	public function __construct($min = null, $max = null) {
		if ($min !== null) {
			Assert::isInteger($min);
		}

		if ($max !== null) {
			Assert::isInteger($max);
		}

		parent::__construct($min, $max);
	}

	/**
	 * @return Range
	 **/
	public static function create($min = null, $max = null) {
		return new self($min, $max);
	}

	/**
	 * @throws WrongArgumentException
	 * @return Range
	 **/
	public function setMin($min = null) {
		if ($min !== null) {
			Assert::isInteger($min);
		} else {
			return $this;
		}

		return parent::setMin($min);
	}

	/**
	 * @throws WrongArgumentException
	 * @return Range
	 **/
	public function setMax($max = null) {
		if ($max !== null) {
			Assert::isInteger($max);
		} else {
			return $this;
		}

		return parent::setMax($max);
	}
}
