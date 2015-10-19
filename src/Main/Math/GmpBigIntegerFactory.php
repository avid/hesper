<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Math;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Singleton;

/**
 * Class GmpBigIntegerFactory
 * @package Hesper\Main\Math
 */
final class GmpBigIntegerFactory extends BigNumberFactory {

	/**
	 * @return GmpBigIntegerFactory
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	/**
	 * @return GmpBigInteger
	 **/
	public function makeNumber($number, $base = 10) {
		return GmpBigInteger::make($number, $base);
	}

	/**
	 * @return GmpBigInteger
	 **/
	public function makeFromBinary($binary) {
		return GmpBigInteger::makeFromBinary($binary);
	}

	/**
	 * @return GmpBigInteger
	 **/
	public function makeRandom($stop, RandomSource $source) {
		if (is_string($stop)) {
			$stop = $this->makeNumber($stop);
		} elseif ($stop instanceof BigInteger && !$stop instanceof GmpBigInteger) {
			$stop = $this->makeNumber($stop->toString());
		}

		Assert::isTrue($stop instanceof GmpBigInteger);

		$numBytes = ceil(log($stop->floatValue(), 2) / 8);

		return $this->makeFromBinary("\x00" . $source->getBytes($numBytes))->mod($stop);
	}
}
