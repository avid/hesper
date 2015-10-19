<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Criteria;

use Hesper\Core\Base\StaticFactory;
use Hesper\Core\Logic\LogicalObject;
use Hesper\Core\Logic\MappableObject;
use Hesper\Main\Criteria\Projection\AverageNumberProjection;
use Hesper\Main\Criteria\Projection\ClassProjection;
use Hesper\Main\Criteria\Projection\CrippleClassProjection;
use Hesper\Main\Criteria\Projection\DistinctCountProjection;
use Hesper\Main\Criteria\Projection\GroupByClassProjection;
use Hesper\Main\Criteria\Projection\GroupByPropertyProjection;
use Hesper\Main\Criteria\Projection\HavingProjection;
use Hesper\Main\Criteria\Projection\MappableObjectProjection;
use Hesper\Main\Criteria\Projection\MaximalNumberProjection;
use Hesper\Main\Criteria\Projection\MinimalNumberProjection;
use Hesper\Main\Criteria\Projection\ProjectionChain;
use Hesper\Main\Criteria\Projection\PropertyProjection;
use Hesper\Main\Criteria\Projection\RowCountProjection;
use Hesper\Main\Criteria\Projection\SumProjection;

/**
 * Class Projection
 * @see     http://www.hibernate.org/hib_docs/v3/reference/en/html/querycriteria.html#querycriteria-projection
 * @package Hesper\Main\Criteria
 */
final class Projection extends StaticFactory {

	/**
	 * @return SumProjection
	 **/
	public static function sum($property, $alias = null) {
		return new SumProjection($property, $alias);
	}

	/**
	 * @return AverageNumberProjection
	 **/
	public static function avg($property, $alias = null) {
		return new AverageNumberProjection($property, $alias);
	}

	/**
	 * @return MappableObjectProjection
	 **/
	public static function mappable(MappableObject $object, $alias = null) {
		return new MappableObjectProjection($object, $alias);
	}

	/**
	 * @return MinimalNumberProjection
	 **/
	public static function min($property, $alias = null) {
		return new MinimalNumberProjection($property, $alias);
	}

	/**
	 * @return MaximalNumberProjection
	 **/
	public static function max($property, $alias = null) {
		return new MaximalNumberProjection($property, $alias);
	}

	/**
	 * @return PropertyProjection
	 **/
	public static function property($property, $alias = null) {
		return new PropertyProjection($property, $alias);
	}

	/**
	 * @return RowCountProjection
	 **/
	public static function count($property = null, $alias = null) {
		return new RowCountProjection($property, $alias);
	}

	/**
	 * @return DistinctCountProjection
	 **/
	public static function distinctCount($property = null, $alias = null) {
		return new DistinctCountProjection($property, $alias);
	}

	/**
	 * @return ProjectionChain
	 **/
	public static function chain() {
		return new ProjectionChain();
	}

	/**
	 * @return GroupByPropertyProjection
	 **/
	public static function group($property) {
		return new GroupByPropertyProjection($property);
	}

	/**
	 * @return GroupByClassProjection
	 **/
	public static function groupByClass($class) {
		return new GroupByClassProjection($class);
	}

	/**
	 * @return HavingProjection
	 **/
	public static function having(LogicalObject $logic) {
		return new HavingProjection($logic);
	}

	/**
	 * @return ClassProjection
	 **/
	public static function clazz($className) {
		return new ClassProjection($className);
	}

	/**
	 * @return CrippleClassProjection
	 **/
	public static function crippleClass($className) {
		return new CrippleClassProjection($className);
	}
}
