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
use Hesper\Core\OSQL\SQLFunction;
use Hesper\Main\Criteria\Criteria;

/**
 * Class AggregateProjection
 * @package Hesper\Main\Criteria\Projection
 */
abstract class AggregateProjection extends BaseProjection {

	abstract public function getFunctionName();

	/**
	 * @return JoinCapableQuery
	 **/
	public function process(Criteria $criteria, JoinCapableQuery $query) {
		Assert::isNotNull($this->property);

		return $query->get(SQLFunction::create($this->getFunctionName(), $criteria->getDao()->guessAtom($this->property, $query))->setAlias($this->alias));
	}
}
