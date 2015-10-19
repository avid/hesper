<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Message\Specification;

/**
 * Interface MessageQueueReceiver
 * @package Hesper\Main\Message\Specification
 */
interface MessageQueueReceiver {

	/**
	 * @return Message
	 **/
	public function receive($uTimeout = null);

	/**
	 * @return MessageQueue
	 **/
	public function getQueue();
}
