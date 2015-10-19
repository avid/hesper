<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\OpenId;

/**
 * Interface OpenIdConsumerResult
 * @package Hesper\Main\OpenId
 */
interface OpenIdConsumerResult {

	/**
	 * @return bool
	 **/
	public function isOk();
}
