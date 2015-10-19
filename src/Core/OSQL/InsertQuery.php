<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\DB\Dialect;
use Hesper\Core\Exception\UnsupportedMethodException;
use Hesper\Core\Exception\WrongStateException;
use Hesper\Core\Logic\LogicalObject;

/**
 * Class InsertQuery
 * @package Hesper\Core\OSQL
 */
final class InsertQuery extends InsertOrUpdateQuery {

	/**
	 * @var SelectQuery
	 **/
	protected $select = null;

	/**
	 * @return InsertQuery
	 **/
	public function into($table) {
		$this->table = $table;

		return $this;
	}

	/**
	 * Just an alias to behave like UpdateQuery.
	 * @return InsertQuery
	 **/
	public function setTable($table) {
		return $this->into($table);
	}

	/**
	 * @return InsertQuery
	 **/
	public function setSelect(SelectQuery $select) {
		$this->select = $select;

		return $this;
	}

	public function toDialectString(Dialect $dialect) {
		$query = 'INSERT INTO ' . $dialect->quoteTable($this->table) . ' ';

		if ($this->select === null) {
			$query = $this->toDialectStringValues($query, $dialect);
		} else {
			$query = $this->toDialectStringSelect($query, $dialect);
		}

		$query .= parent::toDialectString($dialect);

		return $query;
	}

	public function where(LogicalObject $exp, $logic = null) {
		throw new UnsupportedMethodException();
	}

	public function andWhere(LogicalObject $exp) {
		throw new UnsupportedMethodException();
	}

	public function orWhere(LogicalObject $exp) {
		throw new UnsupportedMethodException();
	}

	protected function toDialectStringValues($query, Dialect $dialect) {
		$fields = [];
		$values = [];

		foreach ($this->fields as $var => $val) {
			$fields[] = $dialect->quoteField($var);

			if ($val === null) {
				$values[] = $dialect->literalToString(Dialect::LITERAL_NULL);
			} elseif (true === $val) {
				$values[] = $dialect->literalToString(Dialect::LITERAL_TRUE);
			} elseif (false === $val) {
				$values[] = $dialect->literalToString(Dialect::LITERAL_FALSE);
			} elseif ($val instanceof DialectString) {
				$values[] = $val->toDialectString($dialect);
			} else {
				$values[] = $dialect->quoteValue($val);
			}
		}

		if (!$fields || !$values) {
			throw new WrongStateException('what should i insert?');
		}

		$fields = implode(', ', $fields);
		$values = implode(', ', $values);

		return $query . "({$fields}) VALUES ({$values})";
	}

	protected function toDialectStringSelect($query, Dialect $dialect) {
		$fields = [];

		foreach ($this->fields as $var => $val) {
			$fields[] = $dialect->quoteField($var);
		}

		if (!$fields) {
			throw new WrongStateException('what should i insert?');
		}
		if ($this->select->getFieldsCount() != count($fields)) {
			throw new WrongStateException('count of select fields must be equal with count of insert fields');
		}

		$fields = implode(', ', $fields);

		return $query . "({$fields}) (" . $this->select->toDialectString($dialect) . ")";
	}
}
