<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Util\IO;

/**
 * Class OutputStream
 * @package Hesper\Main\Util\Logging
 */
abstract class OutputStream {

	/**
	 * MUST send either whole buffer or nothing at all
	 * or throw IOException
	 * NOTE: if buffer is too large to send it at once and first chunk of
	 * data has been sent successfully, it MUST BLOCK until all data is
	 * sent, or throw IOException. In this case it MUST NOT throw
	 * IOTimedOutException due to impossibility of detecting what data
	 * has been already sent.
	 * It is abnormal state. Maybe you should use some kind of
	 * non-blocking channels instead?
	 **/
	abstract public function write($buffer);

	/**
	 * @return OutputStream
	 **/
	public function flush() {
		/* nop */

		return $this;
	}

	/**
	 * @return OutputStream
	 **/
	public function close() {
		/* nop */

		return $this;
	}
}
