<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Message;

use Hesper\Main\Message\Specification\MessageQueue;

/**
 * Class TextFileQueue
 * @package Hesper\Main\Message
 */
class TextFileQueue implements MessageQueue {

	private $fileName = null;
	private $offset   = null;

	public static function create() {
		return new self;
	}

	public function setFileName($fileName) {
		$this->fileName = $fileName;

		return $this;
	}

	public function getFileName() {
		return $this->fileName;
	}

	public function setOffset($offset) {
		$this->offset = $offset;

		return $this;
	}

	public function getOffset() {
		return $this->offset;
	}
}
