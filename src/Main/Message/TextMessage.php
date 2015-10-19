<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Message;

use Hesper\Core\Base\Timestamp;
use Hesper\Main\Message\Specification\Message;

/**
 * Class TextMessage
 * @package Hesper\Main\Message
 */
final class TextMessage implements Message {

	private $timestamp = null;
	private $text      = null;

	public static function create(Timestamp $timestamp = null) {
		return new self($timestamp);
	}

	public function __construct(Timestamp $timestamp = null) {
		$this->timestamp = $timestamp ?: Timestamp::makeNow();
	}

	public function setTimestamp(Timestamp $timestamp) {
		$this->timestamp = $timestamp;

		return $this;
	}

	public function getTimestamp() {
		return $this->timestamp;
	}

	public function setText($text) {
		$this->text = $text;

		return $this;
	}

	public function getText() {
		return $this->text;
	}
}
