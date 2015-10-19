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
 * Interface OpenIdConsumerAssociationManager
 * @package Hesper\Main\OpenId
 */
interface OpenIdConsumerAssociationManager {

	/**
	 * @return OpenIdConsumerAssociation
	 **/
	public function findByHandle($handle, $type);

	/**
	 * @return OpenIdConsumerAssociation
	 **/
	public function findByServer(HttpUrl $server);

	/**
	 * @return OpenIdConsumerAssociation
	 **/
	public function makeAndSave($handle, $type, $secred, Timestamp $expires, HttpUrl $server);

	/**
	 * @return OpenIdConsumerAssociationManager
	 **/
	public function purgeExpired();

	/**
	 * @return OpenIdConsumerAssociationManager
	 **/
	public function purgeByHandle($handle);
}
