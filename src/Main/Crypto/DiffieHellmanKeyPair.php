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
use Hesper\Main\Math\RandomSource;

/**
 * Class DiffieHellmanKeyPair
 * @see     http://tools.ietf.org/html/rfc2631
 * @package Hesper\Main\Crypto
 */
final class DiffieHellmanKeyPair implements KeyPair {

	private $private    = null;
	private $public     = null;
	private $parameters = null;

	public function __construct(DiffieHellmanParameters $parameters) {
		$this->parameters = $parameters;
	}

	/**
	 * @return DiffieHellmanKeyPair
	 **/
	public static function create(DiffieHellmanParameters $parameters) {
		return new self($parameters);
	}

	/**
	 * @return DiffieHellmanKeyPair
	 **/
	public static function generate(DiffieHellmanParameters $parameters, RandomSource $randomSource) {
		$result = new self($parameters);

		$factory = $parameters->getModulus()->getFactory();

		$result->private = $factory->makeRandom($parameters->getModulus(), $randomSource);

		$result->public = $parameters->getGen()->modPow($result->private, $parameters->getModulus());

		return $result;
	}

	/**
	 * @return DiffieHellmanKeyPair
	 **/
	public function setPrivate(BigInteger $private) {
		$this->private = $private;

		return $this;
	}

	/**
	 * @return BigInteger
	 **/
	public function getPrivate() {
		return $this->private;
	}

	/**
	 * @return DiffieHellmanKeyPair
	 **/
	public function setPublic(BigInteger $public) {
		$this->public = $public;

		return $this;
	}

	/**
	 * @return BigInteger
	 **/
	public function getPublic() {
		return $this->public;
	}

	/**
	 * @return BigInteger
	 **/
	public function makeSharedKey(BigInteger $otherSitePublic) {
		Assert::brothers($this->private, $otherSitePublic);

		return $otherSitePublic->modPow($this->private, $this->parameters->getModulus());
	}
}
