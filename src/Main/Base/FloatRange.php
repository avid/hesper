<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Main\Base;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongArgumentException;

final class FloatRange extends BaseRange {

	public function __construct($min = null, $max = null) {
		if ($min !== null) {
			Assert::isFloat($min);
		}

		if ($max !== null) {
			Assert::isFloat($max);
		}

		parent::__construct($min, $max);
	}

	/**
	 * @return FloatRange
	 **/
	public static function create($min = null, $max = null) {
		return new self($min, $max);
	}

	/**
	 * @throws WrongArgumentException
	 * @return FloatRange
	 **/
	public function setMin($min = null) {
		if ($min !== null) {
			Assert::isFloat($min);
		} else {
			return $this;
		}

		return parent::setMin($min);
	}

	/**
	 * @throws WrongArgumentException
	 * @return FloatRange
	 **/
	public function setMax($max = null) {
		if ($max !== null) {
			Assert::isFloat($max);
		} else {
			return $this;
		}

		return parent::setMax($max);
	}
}
