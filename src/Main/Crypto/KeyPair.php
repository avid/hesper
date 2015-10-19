<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Crypto;

use Hesper\Main\Math\BigInteger;

/**
 * @ingroup Crypto
 **/
interface KeyPair {

	/**
	 * @return BigInteger
	 **/
	public function getPublic();

	/**
	 * @return BigInteger
	 **/
	public function getPrivate();
}
