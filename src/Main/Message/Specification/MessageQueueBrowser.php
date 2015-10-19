<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Message\Specification;

/**
 * Interface MessageQueueBrowser
 * @package Hesper\Main\Message\Specification
 */
interface MessageQueueBrowser {

	/**
	 * @return Message
	 **/
	public function getNextMessage();
}
