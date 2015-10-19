<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Criteria\Projection;

use Hesper\Core\Base\Assert;
use Hesper\Core\OSQL\JoinCapableQuery;
use Hesper\Main\Criteria\Criteria;

/**
 * Class GroupByPropertyProjection
 * @package Hesper\Main\Criteria\Projection
 */
final class GroupByPropertyProjection extends BaseProjection {

	/**
	 * @return JoinCapableQuery
	 **/
	public function process(Criteria $criteria, JoinCapableQuery $query) {
		Assert::isNotNull($this->property);

		return $query->groupBy($criteria->getDao()->guessAtom($this->property, $query));
	}
}
