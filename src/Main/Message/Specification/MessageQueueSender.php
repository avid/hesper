<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Message\Specification;

/**
 * Interface MessageQueueSender
 * @package Hesper\Main\Message\Specification
 */
interface MessageQueueSender {

	/**
	 * @return MessageQueueReceiver
	 **/
	public function send(Message $message);

	/**
	 * @return MessageQueue
	 **/
	public function getQueue();
}
