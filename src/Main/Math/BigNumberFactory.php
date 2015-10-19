<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Math;

use Hesper\Core\Base\Singleton;

/**
 * Class BigNumberFactory
 * @package Hesper\Main\Math
 */
abstract class BigNumberFactory extends Singleton {

	/**
	 * @return BigInteger
	 **/
	abstract public function makeNumber($number, $base = 10);

	/**
	 * make number from big-endian signed two's complement binary notation
	 * @return BigInteger
	 **/
	abstract public function makeFromBinary($binary);

	/**
	 * @param $stop int maximum random number
	 *
	 * @return BigInteger
	 **/
	abstract public function makeRandom($stop, RandomSource $source);
}
