<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Message;

use Hesper\Core\Exception\UnimplementedFeatureException;
use Hesper\Main\Message\Specification\MessageQueue;
use Hesper\Main\Message\Specification\MessageQueueBrowser;

/**
 * Class TextFileQueueBrowser
 * @package Hesper\Main\Message
 */
final class TextFileQueueBrowser implements MessageQueueBrowser {

	private $queue = null;

	/**
	 * @return TextFileQueueBrowser
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return TextFileQueueBrowser
	 **/
	public function setQueue(MessageQueue $queue) {
		$this->queue = $queue;

		return $this;
	}

	/**
	 * @return MessageQueue
	 **/
	public function getQueue() {
		return $this->queue;
	}

	public function getNextMessage() {
		throw new UnimplementedFeatureException;
	}
}
