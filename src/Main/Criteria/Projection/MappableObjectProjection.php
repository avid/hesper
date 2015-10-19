<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Criteria\Projection;

use Hesper\Core\Logic\MappableObject;
use Hesper\Core\OSQL\JoinCapableQuery;
use Hesper\Main\Criteria\Criteria;

/**
 * Class MappableObjectProjection
 * @package Hesper\Main\Criteria\Projection
 */
final class MappableObjectProjection implements ObjectProjection {

	private $mappable = null;
	private $alias    = null;

	public function __construct(MappableObject $mappable, $alias = null) {
		$this->mappable = $mappable;
		$this->alias = $alias;
	}

	/**
	 * @return JoinCapableQuery
	 **/
	public function process(Criteria $criteria, JoinCapableQuery $query) {
		return $query->get($this->mappable->toMapped($criteria->getDao(), $query), $this->alias);
	}
}
