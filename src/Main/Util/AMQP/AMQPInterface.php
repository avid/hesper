<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Main\Util\AMQP;

use Hesper\Core\Exception\MissingElementException;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * AMQP stands for Advanced Message Queue Protocol, which is
 * an open standard middleware layer for message routing and queuing.
 * @package Hesper\Main\Util\AMQP
 */
interface AMQPInterface
{
	/**
	 * @return AMQPInterface
	**/
	public function connect();

	/**
	 * @return AMQPInterface
	**/
	public function disconnect();

	/**
	 * @return AMQPInterface
	**/
	public function reconnect();

	/**
	 * @return boolean
	**/
	public function isConnected();

	/**
	 * @return AMQPInterface
	**/
	public function getLink();


	/**
	 * @param integer $id
	 * @throws WrongArgumentException
	 * @return AMQPChannelInterface
	**/
	public function createChannel($id);

	/**
	 * @throws MissingElementException
	 * @return AMQPChannelInterface
	**/
	public function getChannel($id);


	/**
	 * @return array
	**/
	public function getChannelList();

	/**
	 * @param integer $id
	 * @throws MissingElementException
	 * @return AMQPChannelInterface
	**/
	public function dropChannel($id);


	/**
	 * @return AMQPCredentials
	 */
	public function getCredentials();


	/**
	 * @return bool
	 */
	public function isAlive();


	/**
	 * @param bool $alive
	 * @return AMQPInterface
	 */
	//public function setAlive($alive);
}