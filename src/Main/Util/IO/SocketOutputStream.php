<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Util\IO;

use Hesper\Core\Exception\IOException;
use Hesper\Core\Exception\IOTimedOutException;
use Hesper\Core\Exception\NetworkException;

/**
 * Class SocketOutputStream
 * @package Hesper\Main\Util\IO
 */
final class SocketOutputStream extends OutputStream {

	/**
	 * NOTE: if socket timeout is 1 second, we can block here
	 * over abt 15 seconds. See conventions of OutputStream.
	 * You must set reliable timeout for socket operations if you want to
	 * avoid fatal error on max_execution_time and you must make sure the
	 * buffer is not too large to send it at once to your physical
	 * channel.
	 **/
	const WRITE_ATTEMPTS = 15; // should be enough for everyone (C)

	private $socket = null;

	public function __construct(Socket $socket) {
		$this->socket = $socket;
	}

	/**
	 * @return SocketOutputStream
	 **/
	public function write($buffer) {
		if ($buffer === null) {
			return $this;
		}

		$totalBytes = strlen($buffer);

		try {
			$writtenBytes = $this->socket->write($buffer);

			if ($writtenBytes === false) {
				throw new IOTimedOutException('writing to socket timed out');
			}

			$i = 0;

			while ($writtenBytes < $totalBytes && ($i < self::WRITE_ATTEMPTS)) {
				// 0.1s sleep insurance if something wrong with socket
				usleep(100000);

				$remainingBuffer = substr($buffer, $writtenBytes);

				// NOTE: ignoring timeouts here
				$writtenBytes += $this->socket->write($remainingBuffer);

				++$i;
			}
		} catch (NetworkException $e) {
			throw new IOException($e->getMessage());
		}

		if ($writtenBytes < $totalBytes) {
			throw new IOException('connection is too slow or buffer is too large?');
		}

		return $this;
	}
}
