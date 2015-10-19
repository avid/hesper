<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\OpenId;

use Hesper\Main\Net\HttpUrl;

/**
 * Class OpenIdConsumerPositive
 * @package Hesper\Main\OpenId
 */
final class OpenIdConsumerPositive implements OpenIdConsumerResult {

	private $identity = null;

	public function __construct(HttpUrl $identity) {
		$this->identity = $identity;
	}

	/**
	 * @return HttpUrl
	 **/
	public function getIdentity() {
		return $this->identity;
	}

	public function isOk() {
		return true;
	}
}
