<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry E. Demidov
 */
namespace Hesper\Main\Util;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\StaticFactory;
use Hesper\Core\Logic\Expression;
use Hesper\Core\Logic\LogicalObject;
use Hesper\Core\OSQL\OrderBy;
use Hesper\Main\Criteria\Criteria;
use Hesper\Main\DAO\DAOConnected;

/**
 * Class DaoMoveHelper
 * @package Hesper\Main\Util
 */
final class DaoMoveHelper extends StaticFactory {

	private static $nullValue = 0;
	private static $property  = 'position';

	/* void */
	public static function setNullValue($nullValue) {
		self::$nullValue = $nullValue;
	}

	/* void */
	public static function setProperty($property) {
		self::$property = $property;
	}

	/* void */
	public static function up(DAOConnected $object, LogicalObject $exp = null) {
		$getMethod = 'get' . ucfirst(self::$property);

		Assert::isTrue(method_exists($object, $getMethod));

		$criteria = Criteria::create($object->dao())->addOrder(OrderBy::create(self::$property)->desc())->setLimit(1);

		if ($exp) {
			$criteria->add($exp);
		}

		$oldPosition = $object->$getMethod();

		$criteria->add(Expression::lt(self::$property, $oldPosition));

		if ($upperObject = $criteria->get()) {
			DaoUtils::setNullValue(self::$nullValue);
			DaoUtils::swap($upperObject, $object, self::$property);
		}
	}

	/* void */
	public static function down(DAOConnected $object, LogicalObject $exp = null) {
		$getMethod = 'get' . ucfirst(self::$property);

		Assert::isTrue(method_exists($object, $getMethod));

		$oldPosition = $object->$getMethod();

		$criteria = Criteria::create($object->dao())->add(Expression::gt(self::$property, $oldPosition))->addOrder(OrderBy::create(self::$property)->asc())->setLimit(1);

		if ($exp) {
			$criteria->add($exp);
		}

		if ($lowerObject = $criteria->get()) {
			DaoUtils::setNullValue(self::$nullValue);
			DaoUtils::swap($lowerObject, $object, self::$property);
		}
	}
}
