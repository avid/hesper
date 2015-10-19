<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Aleksey S. Denisov
 */
namespace Hesper\Main\DAO\Uncacher;

use Hesper\Core\Base\Assert;
use Hesper\Core\Cache\Cache;
use Hesper\Main\DAO\Handler\SegmentHandler;
use Hesper\Main\DAO\StorableDAO;
use Hesper\Main\Util\ClassUtils;

/**
 * Class UncacherVoodoDaoWorkerLists
 * @package Hesper\Main\DAO\Uncacher
 */
class UncacherVoodoDaoWorkerLists implements UncacherBase {

	private $handlerList = [];

	/**
	 * @return UncacherBaseDaoWorker
	 */
	public static function create($className, SegmentHandler $handler) {
		return new self($className, $handler);
	}

	public function __construct($className, SegmentHandler $handler) {
		$this->handlerList[$className] = $handler;
	}

	public function getHandlerList() {
		return $this->handlerList;
	}

	/**
	 * @param $uncacher UncacherVoodoDaoWorkerLists same as self class
	 *
	 * @return UncacherBase (this)
	 */
	public function merge(UncacherBase $uncacher) {
		Assert::isInstance($uncacher, get_class($this));

		return $this->mergeSelf($uncacher);
	}

	public function uncache() {
		foreach ($this->handlerList as $className => $handler) {
			$this->uncacheClassName($className, $handler);
		}
	}

	protected function uncacheClassName($className, SegmentHandler $handler) {
		$handler->drop();

		$dao = ClassUtils::callStaticMethod($className . '::dao');

		/* @var $dao StorableDAO */
		return Cache::worker($dao)
		            ->uncacheByQuery($dao->makeSelectHead());
	}

	/**
	 * @param UncacherVoodoDaoWorkerLists $uncacher
	 *
	 * @return UncacherVoodoDaoWorkerLists
	 */
	private function mergeSelf(UncacherVoodoDaoWorkerLists $uncacher) {
		foreach ($uncacher->getHandlerList() as $className => $handler) {
			if (!isset($this->handlerList[$className])) {
				$this->handlerList[$className] = $handler;
			}
		}

		return $this;
	}
}
