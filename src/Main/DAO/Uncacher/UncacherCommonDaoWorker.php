<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Aleksey S. Denisov
 */
namespace Hesper\Main\DAO\Uncacher;

use Hesper\Main\Util\ClassUtils;

/**
 * Class UncacherCommonDaoWorker
 * @package Hesper\Main\DAO\Uncacher
 */
class UncacherCommonDaoWorker extends UncacherBaseDaoWorker {

	/**
	 * @return UncacherCommonDaoWorker
	 */
	public static function create($className, $idKey) {
		return new self($className, $idKey);
	}

	protected function uncacheClassName($className, $idKeys) {
		ClassUtils::callStaticMethod("$className::dao")
		          ->uncacheLists();
		parent::uncacheClassName($className, $idKeys);
	}
}
