<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Aleksey S. Denisov
 */
namespace Hesper\Main\DAO\Uncacher;

use Hesper\Core\Base\Assert;

/**
 * Class UncacherNullDaoWorker
 * @package Hesper\Main\DAO\Uncacher
 */
class UncacherNullDaoWorker implements UncacherBase {

	public static function create() {
		return new self;
	}

	/**
	 * @param $uncacher UncacherNullDaoWorker same as self class
	 *
	 * @return UncacherBase (this)
	 */
	public function merge(UncacherBase $uncacher) {
		Assert::isInstance($uncacher, self::class);

		return $this;
	}

	public function uncache() {
		/* do nothing */
	}
}
