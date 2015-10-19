<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Message;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongStateException;
use Hesper\Main\Message\Specification\Message;
use Hesper\Main\Message\Specification\MessageQueue;
use Hesper\Main\Message\Specification\MessageQueueSender;
use Hesper\Main\Util\IO\FileOutputStream;

/**
 * Class TextFileSender
 * @package Hesper\Main\Message
 */
final class TextFileSender implements MessageQueueSender {

	private $queue  = null;
	private $stream = null;

	public static function create() {
		return new self;
	}

	public function setQueue(MessageQueue $queue) {
		Assert::isInstance($queue, TextFileQueue::class);

		$this->queue = $queue;

		return $this;
	}

	/**
	 * @return MessageQueue
	 **/
	public function getQueue() {
		return $this->queue;
	}

	public function send(Message $message) {
		if (!$this->queue) {
			throw new WrongStateException('you must set the queue first');
		}

		Assert::isInstance($message, 'TextMessage');

		$this->getStream()->write($message->getTimestamp()->toString() . "\t" . str_replace(PHP_EOL, ' ', $message->getText()) . PHP_EOL);
	}

	private function getStream() {
		if (!$this->stream) {
			Assert::isNotNull($this->queue->getFileName());

			$this->stream = FileOutputStream::create($this->queue->getFileName(), true);
		}

		return $this->stream;
	}
}
