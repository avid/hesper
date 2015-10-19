<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\DAO\Handler;

use Hesper\Core\Exception\BaseException;
use Hesper\Main\Util\FileUtils;

/**
 * Class FileSystemSegmentHandler
 * @package Hesper\Main\DAO\Handler
 */
final class FileSystemSegmentHandler implements SegmentHandler {

	private $path = null;

	public function __construct($segmentId) {
		$path = HESPER_TEMP_PATH . 'fsdw' . DIRECTORY_SEPARATOR . $segmentId . DIRECTORY_SEPARATOR;

		try {
			mkdir($path, 0700, true);
		} catch (BaseException $e) {
			// already created in race
		}

		$this->path = $path;
	}

	public function touch($key) {
		try {
			return touch($this->path . $key);
		} catch (BaseException $e) {
			return false;
		}
	}

	public function unlink($key) {
		try {
			return unlink($this->path . $key);
		} catch (BaseException $e) {
			return false;
		}
	}

	public function ping($key) {
		return is_readable($this->path . $key);
	}

	public function drop() {
		// removed, but not created yet
		if (!is_writable($this->path)) {
			return true;
		}

		$toRemove = realpath($this->path) . '.' . microtime(true) . getmypid() . '.' . '.removing';

		try {
			rename($this->path, $toRemove);
		} catch (BaseException $e) {
			// already removed during race
			return true;
		}

		FileUtils::removeDirectory($toRemove, true);

		return true;
	}
}
