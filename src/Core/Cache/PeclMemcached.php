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
 * Connector for PECL's Memcached extension by Antony Dovgal.
 * @see     http://tony2001.phpclub.net/
 * @see     http://pecl.php.net/package/memcache
 * @package Hesper\Core\Cache
 */
class PeclMemcached extends CachePeer {

	const DEFAULT_PORT    = 11211;
	const DEFAULT_HOST    = '127.0.0.1';
	const DEFAULT_TIMEOUT = 1;

	protected $host           = null;
	protected $port           = null;
	protected $persistentId   = null;
	/** @var \Memcached|null */
	private   $instance       = null;
	private   $requestTimeout = null;
	private   $connectTimeout = null;
	private   $triedConnect   = false;

	/**
	 * @return PeclMemcached
	 **/
	public static function create($host = self::DEFAULT_HOST, $port = self::DEFAULT_PORT, $connectTimeout = self::DEFAULT_TIMEOUT) {
		return new self($host, $port, $connectTimeout);
	}

	public function __construct($host = self::DEFAULT_HOST, $port = self::DEFAULT_PORT, $connectTimeout = self::DEFAULT_TIMEOUT) {
		$this->host = $host;
		$this->port = $port;
		$this->connectTimeout = $connectTimeout;
	}

	public function __destruct() {
		if ($this->alive) {
			try {
				$this->instance->quit();
			} catch (BaseException $e) {
				// shhhh.
			}
		}
	}

	public function setPersistentId($persistentId) {
		$this->persistentId = $persistentId;
		return $this;
	}

	public function isAlive() {
		$this->ensureTriedToConnect();

		return parent::isAlive();
	}

	/**
	 * @return PeclMemcached
	 **/
	public function clean() {
		$this->ensureTriedToConnect();

		try {
			$this->instance->flush();
		} catch (BaseException $e) {
			$this->alive = false;
		}

		return parent::clean();
	}

	public function increment($key, $value) {
		$this->ensureTriedToConnect();

		try {
			return $this->instance->increment($key, $value);
		} catch (BaseException $e) {
			return null;
		}
	}

	public function decrement($key, $value) {
		$this->ensureTriedToConnect();

		try {
			return $this->instance->decrement($key, $value);
		} catch (BaseException $e) {
			return null;
		}
	}

	public function getList($indexes) {
		$this->ensureTriedToConnect();

		return ($return = $this->get($indexes)) ? $return : [];
	}

	public function get($index) {
		$this->ensureTriedToConnect();

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
		$this->ensureTriedToConnect();

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
		$this->ensureTriedToConnect();

		try {
			return $this->instance->append($key, $data);
		} catch (BaseException $e) {
			return $this->alive = false;
		}
	}

	/**
	 * @param float $requestTimeout time in seconds
	 *
	 * @return PeclMemcached
	 */
	public function setTimeout($requestTimeout) {
		$this->ensureTriedToConnect();
		$this->requestTimeout = $requestTimeout;
		$this->instance->setOptions([
			\Memcached::OPT_SEND_TIMEOUT => $requestTimeout*1000,
			\Memcached::OPT_RECV_TIMEOUT => $requestTimeout*1000,
		]);

		return $this;
	}

	/**
	 * @return float
	 */
	public function getTimeout() {
		return $this->requestTimeout;
	}

	protected function ensureTriedToConnect() {
		if ($this->triedConnect) {
			return $this;
		}

		$this->triedConnect = true;

		$this->connect();

		return $this;
	}

	protected function store($action, $key, $value, $expires = Cache::EXPIRES_MEDIUM) {
		$this->ensureTriedToConnect();

		try {
			return $this->instance->$action($key, $value, time() + $expires);
		} catch (BaseException $e) {
			return $this->alive = false;
		}
	}

	protected function connect() {
		if( is_null($this->persistentId) ) {
			$this->setPersistentId(self::class);
		}
		$this->instance = new \Memcached();
		$this->instance->addServer($this->host, $this->port);
		$this->instance->setOption(\Memcached::OPT_CONNECT_TIMEOUT, $this->connectTimeout*1000);

		$this->alive = $this->instance->set(self::class, time());
	}
}
