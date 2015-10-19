<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\OpenId;

/**
 * Class OpenIdConsumerFail
 * @package Hesper\Main\OpenId
 */
final class OpenIdConsumerFail implements OpenIdConsumerResult {

	public function isOk() {
		return false;
	}
}
