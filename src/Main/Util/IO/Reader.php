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
 * @ingroup Utils
 **/
abstract class Reader {

	const BLOCK_SIZE = 16384;

	abstract public function close();

	abstract public function read($count);

	public function isEof() {
		return false;
	}

	public function mark() {
		throw new IOException('mark() not supported');
	}

	public function markSupported() {
		return false;
	}

	public function reset() {
		throw new IOException('reset() not supported');
	}

	public function skip($count) {
		return mb_strlen($this->read($count));
	}

	public function available() {
		return 0;
	}

	public function getWhole() {
		$result = null;
		while (!$this->isEof()) {
			$result .= $this->read(self::BLOCK_SIZE);
		}

		return $result;
	}
}
