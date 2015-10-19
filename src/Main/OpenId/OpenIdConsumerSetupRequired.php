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
 * Class OpenIdConsumerSetupRequired
 * @package Hesper\Main\OpenId
 */
final class OpenIdConsumerSetupRequired implements OpenIdConsumerResult {

	private $url = null;

	public function __construct(HttpUrl $url) {
		$this->url = $url;
	}

	/**
	 * @return HttpUrl
	 **/
	public function getUrl() {
		return $this->url;
	}

	public function isOk() {
		return false;
	}
}
