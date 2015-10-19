<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\DAO\Handler;

use Hesper\Core\Cache\Cache;

/**
 * Class CacheSegmentHandler
 * @package Hesper\Main\DAO\Handler
 */
final class CacheSegmentHandler implements SegmentHandler {

	private $index = null;

	public function __construct($segmentId) {
		$this->index = $segmentId;
	}

	public function touch($key) {
		if (!Cache::me()
		          ->append($this->index, $key)
		) {
			return Cache::me()
			            ->set($this->index, '|' . $key, Cache::EXPIRES_FOREVER);
		}

		return true;
	}

	public function unlink($key) {
		if ($data = Cache::me()
		                 ->get($this->index)
		) {
			return Cache::me()
			            ->set($this->index, str_replace('|' . $key, null, $data), Cache::EXPIRES_FOREVER);
		}

		return false;
	}

	public function ping($key) {
		if ($data = Cache::me()
		                 ->get($this->index)
		) {
			return (strpos($data, '|' . $key) !== false);
		}

		return false;
	}

	public function drop() {
		return Cache::me()
		            ->delete($this->index);
	}
}
