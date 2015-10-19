<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Cache;

use Hesper\Core\Base\Singleton;

/**
 * Class BaseLocker
 * @package Hesper\Core\Cache
 */
abstract class BaseLocker extends Singleton {

	protected $pool = [];

	/// acquire lock
	abstract public function get($key);

	/// release lock
	abstract public function free($key);

	/// completely remove lock
	abstract public function drop($key);

	/// drop all acquired/released locks
	public function clean() {
		foreach (array_keys($this->pool) as $key) {
			$this->drop($key);
		}

		return true;
	}
}
