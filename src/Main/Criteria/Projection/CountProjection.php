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
 * Class CountProjection
 * @package Hesper\Main\Criteria\Projection
 */
abstract class CountProjection extends BaseProjection {

	/**
	 * @return JoinCapableQuery
	 **/
	public function process(Criteria $criteria, JoinCapableQuery $query) {
		return $query->get($this->getFunction($criteria, $query), $this->alias);
	}

	/**
	 * @return SQLFunction
	 **/
	protected function getFunction(Criteria $criteria, JoinCapableQuery $query) {
		Assert::isNotNull($this->property);

		return SQLFunction::create('count', $this->property ? $criteria->getDao()->guessAtom($this->property, $query) : $criteria->getDao()->getIdName());
	}
}
