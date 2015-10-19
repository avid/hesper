<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\DB\Dialect;
use Hesper\Core\Logic\LogicalObject;

/**
 * Class UpdateQuery
 * @package Hesper\Core\OSQL
 */
final class UpdateQuery extends InsertOrUpdateQuery implements JoinCapableQuery {

	private $joiner = null;

	public function __construct($table = null) {
		$this->table = $table;
		$this->joiner = new Joiner();
	}

	public function __clone() {
		$this->joiner = clone $this->joiner;
	}

	/**
	 * @return UpdateQuery
	 **/
	public function from($table, $alias = null) {
		$this->joiner->from(new FromTable($table, $alias));

		return $this;
	}

	public function hasJoinedTable($table) {
		return $this->joiner->hasJoinedTable($table);
	}

	/**
	 * @return UpdateQuery
	 **/
	public function join($table, LogicalObject $logic, $alias = null) {
		$this->joiner->join(new SQLJoin($table, $logic, $alias));

		return $this;
	}

	/**
	 * @return UpdateQuery
	 **/
	public function leftJoin($table, LogicalObject $logic, $alias = null) {
		$this->joiner->leftJoin(new SQLLeftJoin($table, $logic, $alias));

		return $this;
	}

	/**
	 * @return UpdateQuery
	 **/
	public function rightJoin($table, LogicalObject $logic, $alias = null) {
		$this->joiner->rightJoin(new SQLRightJoin($table, $logic, $alias));

		return $this;
	}

	/**
	 * @return UpdateQuery
	 **/
	public function setTable($table) {
		$this->table = $table;

		return $this;
	}

	public function toDialectString(Dialect $dialect) {
		$query = 'UPDATE ' . $dialect->quoteTable($this->table) . ' SET ';

		$sets = [];

		foreach ($this->fields as $var => $val) {
			if ($val instanceof DialectString) {
				$sets[] = $dialect->quoteField($var) . ' = (' . $val->toDialectString($dialect) . ')';
			} elseif ($val === null) {
				$sets[] = $dialect->quoteField($var) . ' = ' . $dialect->literalToString(Dialect::LITERAL_NULL);
			} elseif (true === $val) {
				$sets[] = $dialect->quoteField($var) . ' = ' . $dialect->literalToString(Dialect::LITERAL_TRUE);
			} elseif (false === $val) {
				$sets[] = $dialect->quoteField($var) . ' = ' . $dialect->literalToString(Dialect::LITERAL_FALSE);
			} else {
				$sets[] = $dialect->quoteField($var) . ' = ' . $dialect->quoteValue($val);
			}
		}

		return $query . implode(', ', $sets) . $this->joiner->toDialectString($dialect) . parent::toDialectString($dialect);
	}
}
