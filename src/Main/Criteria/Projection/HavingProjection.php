<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry E. Demidov
 */
namespace Hesper\Main\Criteria\Projection;

use Hesper\Core\Logic\LogicalObject;
use Hesper\Core\OSQL\JoinCapableQuery;
use Hesper\Main\Criteria\Criteria;

/**
 * Class HavingProjection
 * @package Hesper\Main\Criteria\Projection
 */
final class HavingProjection implements ObjectProjection {

	private $logic = null;

	public function __construct(LogicalObject $logic) {
		$this->logic = $logic;
	}

	/**
	 * @return JoinCapableQuery
	 **/
	public function process(Criteria $criteria, JoinCapableQuery $query) {
		return $query->having($this->logic->toMapped($criteria->getDao(), $query));
	}
}
