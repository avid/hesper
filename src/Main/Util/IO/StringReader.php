<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry A. Lomash
 */
namespace Hesper\Main\Util\IO;

use Hesper\Core\Exception\IOException;

/**
 * Class StringReader
 * @package Hesper\Main\Util\IO
 */
final class StringReader extends Reader {

	private $string = null;
	private $length = null;

	private $next = 0;
	private $mark = 0;

	/**
	 * @return StringReader
	 **/
	public static function create($string) {
		return new self($string);
	}

	public function __construct($string) {
		$this->string = $string;
		$this->length = mb_strlen($this->string);
	}

	/**
	 * @return StringReader
	 **/
	public function close() {
		$this->string = null;

		return $this;
	}

	public function read($count) {
		$this->ensureOpen();

		if ($this->next >= $this->length) {
			return null;
		}

		$result = mb_substr($this->string, $this->next, $count);

		$this->next += $count;

		return $result;
	}

	/**
	 * @return StringReader
	 **/
	public function mark() {
		$this->ensureOpen();

		$this->mark = $this->next;

		return $this;
	}

	public function markSupported() {
		return true;
	}

	/**
	 * @return StringReader
	 **/
	public function reset() {
		$this->ensureOpen();

		$this->next = $this->mark;

		return $this;
	}

	public function skip($count) {
		$this->ensureOpen();

		if ($this->isEof()) {
			return 0;
		}

		$actualSkip = max(-$this->next, min($this->length - $this->next, $count));

		$this->next += $actualSkip;

		return $actualSkip;
	}

	public function isEof() {
		return ($this->next >= $this->length);
	}

	public function getWhole() {
		return $this->read($this->length - $this->next);
	}

	/* void */
	private function ensureOpen() {
		if ($this->string === null) {
			throw new IOException('Stream closed');
		}
	}
}
