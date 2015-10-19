<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 */
namespace Hesper\Main\Criteria\Projection;

use Hesper\Core\Base\Assert;
use Hesper\Core\OSQL\DBField;
use Hesper\Core\OSQL\DBNull;
use Hesper\Core\OSQL\DBValue;
use Hesper\Core\OSQL\JoinCapableQuery;

/**
 * Cripple Class Projection
 * Allow to make projection of class without some properies
 * In SQL-query this properties will be changed to NULL
 */
class CrippleClassProjection extends ClassProjection {

	private $excludedFields = array();

	/**
	 * @param string $class
	 * @return CrippleClassProjection
	 */
	public static function create($class) {
		return new self($class);
	}

	/**
	 * @param string $field
	 * @return CrippleClassProjection
	 */
	public function excludeField($field, $value = null) {
		Assert::isString($field);
		$this->excludedFields[$field] = $value;
		return $this;
	}

	/* void */
	protected function subProcess(JoinCapableQuery $query, DBField $field) {
		// if need to exclude change field to NULL
		if( array_key_exists($field->getField(), $this->excludedFields) ) {
			$value = $this->excludedFields[$field->getField()];
			$query->get((is_null($value) ? new DBNull() : new DBValue($value)), $field->getField());
		} else {
			$query->get($field);
		}
	}

}