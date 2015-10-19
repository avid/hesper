<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Cache;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Instantiatable;
use Hesper\Core\Base\Singleton;

/**
 * Single access point to application-wide locker implementation.
 * @see     SystemFiveLocker for default locker
 * @see     FileLocker for 'universal' locker
 * @see     DirectoryLocker for slow and dirty locker
 * @see     eAcceleratorLocker for eA-based locker
 * @package Hesper\Core\Cache
 */
final class SemaphorePool extends BaseLocker implements Instantiatable {

	private static $lockerName = DirectoryLocker::class;
	private static $locker     = null;

	protected function __construct() {
		self::$locker = Singleton::getInstance(self::$lockerName);
	}

	public static function setDefaultLocker($name) {
		Assert::classExists($name);

		self::$lockerName = $name;
		self::$locker = Singleton::getInstance($name);
	}

	/**
	 * @return SemaphorePool
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function get($key) {
		return self::$locker->get($key);
	}

	public function free($key) {
		return self::$locker->free($key);
	}

	public function drop($key) {
		return self::$locker->drop($key);
	}

	public function clean() {
		return self::$locker->clean();
	}

	public function __destruct() {
		self::$locker->clean();
	}
}
