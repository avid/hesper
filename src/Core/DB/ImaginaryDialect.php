<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\DB;

use Hesper\Core\OSQL\DBColumn;
use Hesper\Core\OSQL\DBValue;
use Hesper\Core\OSQL\DialectString;

/**
 * Inexistent imaginary helper for OSQL's Query self-identification.
 * @package Hesper\Core\DB
 */
final class ImaginaryDialect extends Dialect {

	private static $self = null;

	/**
	 * @return ImaginaryDialect
	 **/
	public static function me() {
		if (!self::$self) {
			self::$self = new self();
		}

		return self::$self;
	}

	public function preAutoincrement(DBColumn $column) {
		return null;
	}

	public function postAutoincrement(DBColumn $column) {
		return 'AUTOINCREMENT';
	}

	public function quoteValue($value) {
		return $value;
	}

	public function quoteField($field) {
		return $field;
	}

	public function quoteTable($table) {
		return $table;
	}

	public function hasTruncate() {
		return false;
	}

	public function hasMultipleTruncate() {
		return false;
	}

	public function hasReturning() {
		return false;
	}

	public function fieldToString($field) {
		return $field instanceof DialectString ? $field->toDialectString($this) : $field;
	}

	public function valueToString($value) {
		return $value instanceof DBValue ? $value->toDialectString($this) : $value;
	}

	public function fullTextSearch($field, $words, $logic) {
		return '("' . $this->fieldToString($field) . '" CONTAINS "' . implode($logic, $words) . '")';
	}

	public function fullTextRank($field, $words, $logic) {
		return '(RANK BY "' . $this->fieldToString($field) . '" WHICH CONTAINS "' . implode($logic, $words) . '")';
	}

	public function quoteIpInRange($range, $ip) {
		$string = '';

		if ($ip instanceof DialectString) {
			$string .= $ip->toDialectString($this);
		} else {
			$string .= $this->quoteValue($ip);
		}

		$string .= ' in (';

		if ($range instanceof DialectString) {
			$string .= $range->toDialectString($this);
		} else {
			$string .= $this->quoteValue($range);
		}

		$string .= ')';

		return $string;
	}
}
