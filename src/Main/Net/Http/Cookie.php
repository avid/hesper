<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Evgeny V. Kokovikhin
 */
namespace Hesper\Main\Net\Http;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongStateException;
use Hesper\Main\Base\CollectionItem;

/**
 * Class Cookie
 * @package Hesper\Main\Net\Http
 */
final class Cookie extends CollectionItem {

	private $name     = null;
	private $value    = null;
	private $expire   = 0;
	private $path     = null;
	private $domain   = null;
	private $secure   = false;
	private $httpOnly = false;

	/**
	 * @return Cookie
	 **/
	public static function create($name) {
		return new self($name);
	}

	public function __construct($name) {
		$this->id = $this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function setValue($value) {
		$this->value = $value;

		return $this;
	}

	public function getValue() {
		return $this->value;
	}

	public function setMaxAge($expire) {
		Assert::isInteger($expire);

		$this->expire = $expire;

		return $this;
	}

	public function getMaxAge() {
		return $this->expire;
	}

	public function setPath($path) {
		Assert::isString($path);

		$this->path = $path;

		return $this;
	}

	public function getPath() {
		return $this->path;
	}

	public function setDomain($domain) {
		Assert::isString($domain);

		$this->domain = $domain;

		return $this;
	}

	public function getDomain() {
		return $this->domain;
	}

	public function setSecure($secure = true) {
		$this->secure = (boolean)$secure;

		return $this;
	}

	public function getSecure() {
		return $this->secure;
	}

	public function setHttpOnly($httpOnly = true) {
		$this->httpOnly = (boolean)$httpOnly;

		return $this;
	}

	public function getHttpOnly() {
		return $this->httpOnly;
	}

	public function httpSet() {
		if (headers_sent()) {
			throw new WrongStateException('headers already send');
		}

		return setcookie($this->getName(), $this->getValue(), ($this->getMaxAge() === 0) ? 0 : $this->getMaxAge() + time(), $this->getPath(), $this->getDomain(), $this->getSecure(), $this->getHttpOnly());
	}
}
