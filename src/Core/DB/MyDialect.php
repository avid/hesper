<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\DB;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Identifier;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\OSQL\DataType;
use Hesper\Core\OSQL\DBColumn;

/**
 * MySQL dialect.
 * @package Hesper\Core\DB
 * @see     http://www.mysql.com/
 * @see     http://www.php.net/mysql
 */
class MyDialect extends Dialect {

	const IN_BOOLEAN_MODE = 1;

	public function quoteValue($value) {
		/// @see Sequenceless for this convention

		if ($value instanceof Identifier && !$value->isFinalized()) {
			return "''";
		} // instead of 'null', to be compatible with v. 4

		return "'" . mysql_real_escape_string($value, $this->getLink()) . "'";
	}

	public function quoteField($field) {
		if (strpos($field, '.') !== false) {
			throw new WrongArgumentException();
		} elseif (strpos($field, '::') !== false) {
			throw new WrongArgumentException();
		}

		return "`{$field}`";
	}

	public function quoteTable($table) {
		return "`{$table}`";
	}

	public static function dropTableMode($cascade = false) {
		return null;
	}

	public static function timeZone($exist = false) {
		return null;
	}

	public function quoteBinary($data) {
		return "'" . mysql_real_escape_string($data) . "'";
	}

	public function typeToString(DataType $type) {
		if ($type->getId() == DataType::BINARY) {
			return 'BLOB';
		}

		if ($type->getId() == DataType::HSTORE) {
			return 'TEXT';
		}

		if ($type->getId() == DataType::UUID) {
			return 'CHAR(4)';
		}

		return parent::typeToString($type);
	}

	public function hasTruncate() {
		return true;
	}

	public function hasMultipleTruncate() {
		return false;
	}

	public function hasReturning() {
		return false;
	}

	public function preAutoincrement(DBColumn $column) {
		$column->setDefault(null);

		return null;
	}

	public function postAutoincrement(DBColumn $column) {
		return 'AUTO_INCREMENT';
	}

	public function fullTextSearch($fields, $words, $logic) {
		return ' MATCH (' . implode(', ', array_map([$this, 'fieldToString'], $fields)) . ') AGAINST (' . self::prepareFullText($words, $logic) . ')';
	}

	private static function prepareFullText($words, $logic) {
		Assert::isArray($words);

		$retval = self::quoteValue(implode(' ', $words));

		if (self::IN_BOOLEAN_MODE === $logic) {
			return addcslashes($retval, '+-<>()~*"') . ' ' . 'IN BOOLEAN MODE';
		} else {
			return $retval;
		}
	}
}
