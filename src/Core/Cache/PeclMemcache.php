<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Cache;

use Hesper\Core\Exception\BaseException;

/**
 * Connector for PECL's Memcache extension by Antony Dovgal.
 * @see     http://tony2001.phpclub.net/
 * @see     http://pecl.php.net/package/memcache
 * @package Hesper\Core\Cache
 */
class PeclMemcache extends CachePeer {

	const DEFAULT_PORT = 11211;
	const DEFAULT_HOST = '127.0.0.1';

	/** @var \Memcache|null */
	private $instance = null;

	/**
	 * @return PeclMemcache
	 **/
	public static function create($host = self::DEFAULT_HOST, $port = self::DEFAULT_PORT) {
		return new self($host, $port);
	}

	public function __construct($host = self::DEFAULT_HOST, $port = self::DEFAULT_PORT) {
		$this->instance = new \Memcache();

		try {
			try {
				$this->instance->pconnect($host, $port);
			} catch (BaseException $e) {
				$this->instance->connect($host, $port);
			}

			$this->alive = true;
		} catch (BaseException $e) {
			// bad luck.
		}
	}

	public function __destruct() {
		if ($this->alive) {
			try {
				$this->instance->close();
			} catch (BaseException $e) {
				// shhhh.
			}
		}
	}

	/**
	 * @return PeclMemcache
	 **/
	public function clean() {
		try {
			$this->instance->flush();
		} catch (BaseException $e) {
			$this->alive = false;
		}

		return parent::clean();
	}

	public function increment($key, $value) {
		try {
			return $this->instance->increment($key, $value);
		} catch (BaseException $e) {
			return null;
		}
	}

	public function decrement($key, $value) {
		try {
			return $this->instance->decrement($key, $value);
		} catch (BaseException $e) {
			return null;
		}
	}

	public function getList($indexes) {
		return ($return = $this->get($indexes)) ? $return : [];
	}

	public function get($index) {
		try {
			return $this->instance->get($index);
		} catch (BaseException $e) {
			if (strpos($e->getMessage(), 'Invalid key') !== false) {
				return null;
			}

			$this->alive = false;

			return null;
		}
	}

	public function delete($index) {
		try {
			// second parameter required, wrt new memcached protocol:
			// delete key 0 (see process_delete_command in the memcached.c)
			// Warning: it is workaround!
			return $this->instance->delete($index, 0);
		} catch (BaseException $e) {
			return $this->alive = false;
		}
	}

	public function append($key, $data) {
		try {
			return $this->instance->append($key, $data);
		} catch (BaseException $e) {
			return $this->alive = false;
		}
	}

	protected function store($action, $key, $value, $expires = Cache::EXPIRES_MEDIUM) {
		try {
			return $this->instance->$action($key, $value, $this->compress ? MEMCACHE_COMPRESSED : false, $expires);
		} catch (BaseException $e) {
			return $this->alive = false;
		}
	}
}
