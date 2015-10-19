<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Crypto;

use Hesper\Core\Base\Assert;
use Hesper\Main\Math\BigInteger;

/**
 * Class DiffieHellmanParameters
 * @see     http://tools.ietf.org/html/rfc2631
 * @package Hesper\Main\Crypto
 */
final class DiffieHellmanParameters {

	private $gen     = null;
	private $modulus = null;

	public function __construct(BigInteger $gen, BigInteger $modulus) {
		Assert::brothers($gen, $modulus);

		$this->gen = $gen;
		$this->modulus = $modulus;
	}

	/**
	 * @return DiffieHellmanParameters
	 **/
	public static function create(BigInteger $gen, BigInteger $modulus) {
		return new self($gen, $modulus);
	}

	/**
	 * @return BigInteger
	 **/
	public function getGen() {
		return $this->gen;
	}

	/**
	 * @return BigInteger
	 **/
	public function getModulus() {
		return $this->modulus;
	}
}
