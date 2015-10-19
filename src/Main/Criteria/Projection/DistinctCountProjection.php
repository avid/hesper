<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Criteria\Projection;

use Hesper\Core\OSQL\JoinCapableQuery;
use Hesper\Core\OSQL\SQLFunction;
use Hesper\Main\Criteria\Criteria;

/**
 * Class DistinctCountProjection
 * @package Hesper\Main\Criteria\Projection
 */
final class DistinctCountProjection extends CountProjection {

	/**
	 * @return SQLFunction
	 **/
	protected function getFunction(Criteria $criteria, JoinCapableQuery $query) {
		return parent::getFunction($criteria, $query)->setAggregateDistinct();
	}
}
