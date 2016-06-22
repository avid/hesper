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
use Hesper\Core\Base\Instantiatable;
use Hesper\Core\Logic\PostfixUnaryExpression;
use Hesper\Core\OSQL\DataType;
use Hesper\Core\OSQL\DBColumn;

/**
 * SQLite dialect.
 * @package Hesper\Core\DB
 * @see     http://www.sqlite.org/
 */
class LiteDialect extends Dialect implements Instantiatable {

	public function quoteValue($value) {
		/// @see Sequenceless for this convention

		if ($value instanceof Identifier && !$value->isFinalized()) {
			return 'null';
		}

		if (Assert::checkInteger($value)) {
			return $value;
		}

		return "'" . sqlite_escape_string($value) . "'";
	}

	public static function dropTableMode($cascade = false) {
		return null;
	}

	public function quoteBinary($data) {
		return "'" . sqlite_udf_encode_binary($data) . "'";
	}

	public function unquoteBinary($data) {
		return sqlite_udf_decode_binary($data);
	}

	public function typeToString(DataType $type) {
		switch ($type->getId()) {
			case DataType::BIGINT:

				return 'INTEGER';

			case DataType::BINARY:

				return 'BLOB';
		}

		return parent::typeToString($type);
	}

	public function logicToString($logic) {
		switch ($logic) {
			case PostfixUnaryExpression::IS_FALSE:
				return '= ' . $this->quoteValue('0');
			case PostfixUnaryExpression::IS_TRUE:
				return '= ' . $this->quoteValue('1');
		}

		return parent::logicToString($logic);
	}

	public function literalToString($literal) {
		switch ($literal) {
			case self::LITERAL_FALSE:
				return $this->quoteValue('0');
			case self::LITERAL_TRUE:
				return $this->quoteValue('1');
		}

		return parent::literalToString($literal);
	}

	public function preAutoincrement(DBColumn $column) {
		self::checkColumn($column);

		return null;
	}

	public function postAutoincrement(DBColumn $column) {
		self::checkColumn($column);

		return null; // or even 'AUTOINCREMENT'?
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

	private static function checkColumn(DBColumn $column) {
		$type = $column->getType();

		Assert::isTrue(($type->getId() == DataType::BIGINT || $type->getId() == DataType::INTEGER) && $column->isPrimaryKey());
	}
}
