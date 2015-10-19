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

interface TaggableHandler {

	// get tag list for object
	public function getCacheObjectTags(IdentifiableObject $object, $className);

	// get tag list for object
	public function getUncacheObjectTags(IdentifiableObject $object, $className);

	// get tag list for query
	public function getQueryTags(SelectQuery $query, $className);

	// get tag list for null object
	public function getNullObjectTags($id, $className);

	// get default tag list
	public function getDefaultTags($className);

}
