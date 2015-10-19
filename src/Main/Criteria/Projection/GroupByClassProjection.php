<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Dmitry A. Lomash
 */
namespace Hesper\Main\Criteria\Projection;

use Hesper\Core\OSQL\DBField;
use Hesper\Core\OSQL\JoinCapableQuery;

/**
 * Class GroupByClassProjection
 * @package Hesper\Main\Criteria\Projection
 */
final class GroupByClassProjection extends ClassProjection {

	/**
	 * @return GroupByClassProjection
	 **/
	public static function create($class) {
		return new self($class);
	}

	/* void */
	protected function subProcess(JoinCapableQuery $query, DBField $field) {
		$query->groupBy($field);
	}
}
