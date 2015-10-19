<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Solomatin Alexandr
 */
namespace Hesper\Main\DAO\Worker;

use Hesper\Core\Base\IdentifiableObject;
use Hesper\Core\OSQL\SelectQuery;

/**
 * Logic like in CacheDaoWorker
 * @package Hesper\Main\DAO\Worker
 */
class TaggableLayerHandler implements TaggableHandler {

	public function getCacheObjectTags(IdentifiableObject $object, $className) {
		return [$className];
	}

	public function getUncacheObjectTags(IdentifiableObject $object, $className) {
		return [$className];
	}

	public function getQueryTags(SelectQuery $query, $className) {
		return [$className];
	}

	public function getNullObjectTags($id, $className) {
		return $this->getDefaultTags($className);
	}

	public function getDefaultTags($className) {
		return [$className];
	}
}
