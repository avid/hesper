<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Math;

use Hesper\Core\Base\Stringable;

/**
 * Interface BigInteger
 * @package Hesper\Main\Math
 */
interface BigInteger extends Stringable {

	/**
	 * @return BigNumberFactory
	 **/
	public static function getFactory();

	/**
	 * @return BigInteger
	 **/
	public function add(BigInteger $x);

	public function compareTo(BigInteger $x);

	/**
	 * @return BigInteger
	 **/
	public function mod(BigInteger $mod);

	/**
	 * @return BigInteger
	 **/
	public function pow(BigInteger $exp);

	/**
	 * @return BigInteger
	 **/
	public function modPow(BigInteger $exp, BigInteger $mod);

	/**
	 * @return BigInteger
	 **/
	public function subtract(BigInteger $x);

	/**
	 * @return BigInteger
	 **/
	public function mul(BigInteger $x);

	/**
	 * @return BigInteger
	 **/
	public function div(BigInteger $x);

	/**
	 * convert to big-endian signed two's complement notation
	 **/
	public function toBinary();

	public function intValue();

	public function floatValue();
}
