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
 * Class MessageSegmentHandler
 * @package Hesper\Main\DAO\Handler
 */
final class MessageSegmentHandler implements SegmentHandler {

	private $id = null;

	public function __construct($segmentId) {
		$this->id = $segmentId;
	}

	public function touch($key) {
		try {
			$q = msg_get_queue($this->id, HESPER_IPC_PERMS);
		} catch (BaseException $e) {
			// race
			return false;
		}

		try {
			return msg_send($q, $key, 1, false, false);
		} catch (BaseException $e) {
			// queue is full, rotate it.
			return msg_remove_queue($q);
		}
	}

	public function unlink($key) {
		try {
			$q = msg_get_queue($this->id, HESPER_IPC_PERMS);
		} catch (BaseException $e) {
			// race
			return false;
		}

		$type = $msg = null;

		return msg_receive($q, $key, $type, 2, $msg, false, MSG_IPC_NOWAIT);
	}

	public function ping($key) {
		try {
			$q = msg_get_queue($this->id, HESPER_IPC_PERMS);
		} catch (BaseException $e) {
			// race
			return false;
		}

		$type = $msg = null;

		// YANETUT
		if (msg_receive($q, $key, $type, 2, $msg, false, MSG_IPC_NOWAIT)) {
			try {
				msg_send($q, $key, 1, false, false);
			} catch (BaseException $e) {/* lost key due to race */
			}

			return true;
		}

		return false;
	}

	public function drop() {
		try {
			$q = msg_get_queue($this->id, HESPER_IPC_PERMS);
		} catch (BaseException $e) {
			// removed in race
			return true;
		}

		if (!msg_remove_queue($q)) {
			// trying to flush manually
			$type = $msg = null;

			while (msg_receive($q, 0, $type, 2, $msg, false, MSG_IPC_NOWAIT)) {
				// do nothing
			}
		}

		return true;
	}
}
