<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Message;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Timestamp;
use Hesper\Core\Exception\WrongStateException;
use Hesper\Main\Message\Specification\Message;
use Hesper\Main\Message\Specification\MessageQueue;
use Hesper\Main\Message\Specification\MessageQueueReceiver;
use Hesper\Main\Util\IO\FileInputStream;

/**
 * Class TextFileReceiver
 * @package Hesper\Main\Message
 */
final class TextFileReceiver implements MessageQueueReceiver {

	private $queue  = null;
	private $stream = null;

	/**
	 * @return TextFileReceiver
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return TextFileReceiver
	 **/
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

	/**
	 * @return Message
	 **/
	public function receive($uTimeout = null) {
		if (!$this->queue) {
			throw new WrongStateException('you must set the queue first');
		}

		if ($uTimeout && $this->getStream()->isEof()) {
			usleep($uTimeout);
		}

		$string = $this->getStream()->readString();

		if (!$string && $this->getStream()->isEof()) {
			return null;
		}

		$this->getQueue()->setOffset($this->getStream()->getOffset());

		$string = rtrim($string, PHP_EOL);

		$chunks = preg_split("/\t/", $string, 2);

		$time = isset($chunks[0]) ? $chunks[0] : null;
		$text = isset($chunks[1]) ? $chunks[1] : null;

		Assert::isNotNull($time);

		$result = TextMessage::create(Timestamp::create($time))->setText($text);

		return $result;
	}

	/**
	 * @return FileInputStream
	 **/
	private function getStream() {
		if (!$this->stream) {
			Assert::isNotNull($this->queue->getFileName());

			$this->stream = FileInputStream::create($this->queue->getFileName())->seek($this->queue->getOffset());
		}

		return $this->stream;
	}
}
