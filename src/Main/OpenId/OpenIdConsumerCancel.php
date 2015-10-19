<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\OpenId;

/**
 * Class OpenIdConsumerCancel
 * @package Hesper\Main\OpenId
 */
final class OpenIdConsumerCancel implements OpenIdConsumerResult {

	public function isOk() {
		return false;
	}
}
