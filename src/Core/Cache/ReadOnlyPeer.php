<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator KAlexander A. Klestov
 */
namespace Hesper\Core\Cache;

use Hesper\Core\Exception\UnsupportedMethodException;

/**
 * Cache with read-only access.
 * @package Hesper\Core\Cache
 */
final class ReadOnlyPeer extends CachePeer {

	/**
	 * @var CachePeer
	 */
	private $innerPeer = null;

	/**
	 * @return ReadOnlyPeer
	 */
	public static function create(CachePeer $peer) {
		return new ReadOnlyPeer($peer);
	}

	public function __construct(CachePeer $peer) {
		$this->innerPeer = $peer;
	}

	public function isAlive() {
		return $this->innerPeer->isAlive();
	}

	public function mark($className) {
		return $this->innerPeer->mark($className);
	}

	public function get($key) {
		return $this->innerPeer->get($key);
	}

	public function getList($indexes) {
		return $this->innerPeer->getList($indexes);
	}

	public function clean() {
		throw new UnsupportedMethodException();
	}

	public function increment($key, $value) {
		throw new UnsupportedMethodException();
	}

	public function decrement($key, $value) {
		throw new UnsupportedMethodException();
	}

	public function delete($index, $time = null) {
		throw new UnsupportedMethodException();
	}

	public function append($key, $data) {
		throw new UnsupportedMethodException();
	}

	protected function store($method, $index, $value, $expires = Cache::EXPIRES_MINIMUM) {
		throw new UnsupportedMethodException();
	}
}
