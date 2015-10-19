<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Util\IO;

use Hesper\Core\Exception\IOException;

/**
 * Class InputStream
 * @package Hesper\Main\Util\IO
 */
abstract class InputStream {

	/**
	 * reads a maximum of $length bytes
	 * returns null on eof or if length == 0.
	 * Otherwise MUST return at least one byte
	 * or throw IOException
	 * NOTE: if length is too large to read all data at once and eof has
	 * not been reached, it MUST BLOCK until all data is read or eof is
	 * reached or throw IOException.
	 * It is abnormal state. Maybe you should use some kind of
	 * non-blocking channels instead?
	 **/
	abstract public function read($length);

	abstract public function isEof();

	/**
	 * @return InputStream
	 **/
	public function mark() {
		/* nop */

		return $this;
	}

	public function markSupported() {
		return false;
	}

	public function reset() {
		throw new IOException('mark has been invalidated');
	}

	public function skip($count) {
		return strlen($this->read($count));
	}

	public function available() {
		return 0;
	}

	/**
	 * @return InputStream
	 **/
	public function close() {
		/* nop */

		return $this;
	}
}
