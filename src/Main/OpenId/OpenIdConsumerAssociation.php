<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\OpenId;

use Hesper\Core\Base\Timestamp;
use Hesper\Main\Net\HttpUrl;

/**
 * Interface OpenIdConsumerAssociation
 * @package Hesper\Main\OpenId
 */
interface OpenIdConsumerAssociation {

	public function getHandle();

	public function getType();

	public function getSecret();

	/**
	 * @return Timestamp
	 **/
	public function getExpires();

	/**
	 * @return HttpUrl
	 **/
	public function getServer();
}
