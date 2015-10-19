<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\DAO\Handler;

use Hesper\Core\Exception\BaseException;

/**
 * Class SharedMemorySegmentHandler
 * @package Hesper\Main\DAO\Handler
 */
final class SharedMemorySegmentHandler implements SegmentHandler {

	const SEGMENT_SIZE = 2097152; // 2 ^ 21

	private $id = null;

	public function __construct($segmentId) {
		$this->id = $segmentId;
	}

	public function touch($key) {
		try {
			$shm = shm_attach($this->id, self::SEGMENT_SIZE, HESPER_IPC_PERMS);
		} catch (BaseException $e) {
			return false;
		}

		try {
			$result = shm_put_var($shm, $key, true);
			shm_detach($shm);
		} catch (BaseException $e) {
			// not enough shared memory left, rotate it.
			shm_detach($shm);

			return $this->drop();
		}

		return $result;
	}

	public function unlink($key) {
		try {
			$shm = shm_attach($this->id, self::SEGMENT_SIZE, HESPER_IPC_PERMS);
		} catch (BaseException $e) {
			return false;
		}

		try {
			$result = shm_remove_var($shm, $key);
		} catch (BaseException $e) {
			// non existent key
			$result = false;
		}

		shm_detach($shm);

		return $result;
	}

	public function ping($key) {
		try {
			$shm = shm_attach($this->id, self::SEGMENT_SIZE, HESPER_IPC_PERMS);
		} catch (BaseException $e) {
			return false;
		}

		try {
			$result = shm_get_var($shm, $key);
		} catch (BaseException $e) {
			// variable key N doesn't exist, bleh
			$result = false;
		}

		shm_detach($shm);

		return $result;
	}

	public function drop() {
		try {
			$shm = shm_attach($this->id, self::SEGMENT_SIZE, HESPER_IPC_PERMS);
		} catch (BaseException $e) {
			return false;
		}

		$result = shm_remove($shm);

		shm_detach($shm);

		return $result;
	}
}
