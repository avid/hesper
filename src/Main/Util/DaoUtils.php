<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Nickolay G. Korolyov
 */
namespace Hesper\Main\Util;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\StaticFactory;
use Hesper\Core\DB\DBPool;
use Hesper\Core\Exception\DatabaseException;
use Hesper\Core\Logic\Expression;
use Hesper\Core\OSQL\OSQL;
use Hesper\Main\DAO\DAOConnected;
use Hesper\Main\DAO\StorableDAO;

/**
 * Class DaoUtils
 * @package Hesper\Main\Util
 */
final class DaoUtils extends StaticFactory {

	private static $nullValue = 0;

	/* void */
	public static function swap(DAOConnected $first, DAOConnected $second, $property = 'position') {
		Assert::isTrue(get_class($first) === get_class($second));

		$setMethod = 'set' . ucfirst($property);
		$getMethod = 'get' . ucfirst($property);

		Assert::isTrue(method_exists($first, $setMethod) && method_exists($first, $getMethod));

		/** @var StorableDAO $dao */
		$dao = $first->dao();
		$db = DBPool::me()->getByDao($dao);

		$oldPosition = $first->$getMethod();
		$newPosition = $second->$getMethod();

		$db->begin();

		$e = null;

		try {
			$dao->save($first->$setMethod(self::$nullValue));

			$dao->save($second->$setMethod($oldPosition));

			$dao->save($first->$setMethod($newPosition));

			$db->commit();
		} catch (DatabaseException $e) {
			$db->rollback();
		}

		$dao->uncacheByIds([$first->getId(), $second->getId()]);

		if ($e) {
			throw $e;
		}
	}

	/* void */
	public static function setNullValue($nullValue) {
		self::$nullValue = $nullValue;
	}

	public static function increment(DAOConnected &$object, array $fields /* fieldName => value */, $refreshCurrent = true, /*UpdateQuery*/
	                                 $query = null) {
		$objectDao = $object->dao();

		if ($query) {
			$updateQuery = $query;
		} else {
			$updateQuery = OSQL::update()->setTable($objectDao->getTable())->where(Expression::eqId('id', $object));
		}

		$mapping = $objectDao->getProtoClass()->getMapping();

		foreach ($mapping as $field => $column) {
			if (isset($fields[$field])) {
				$updateQuery->set($column, Expression::add($column, $fields[$field]));
			}
		}

		$updateCount = DBPool::getByDao($objectDao)->queryCount($updateQuery);

		if ($query) {
			$objectDao->uncacheLists();
		} else {
			$objectDao->uncacheById($object->getId());
		}

		if ($refreshCurrent && !$query) {
			$object = $objectDao->getById($object->getId());
		}

		return $updateCount;
	}
}
