<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\DB\Dialect;
use Hesper\Core\Exception\WrongStateException;

/**
 * Reference for actual DB-table column.
 * @package Hesper\Core\OSQL
 */
final class DBField extends Castable implements SQLTableName {

	private $field = null;
	private $table = null;

	public function __construct($field, $table = null) {
		$this->field = $field;

		if ($table) {
			$this->setTable($table);
		}
	}

	/**
	 * @return DBField
	 **/
	public static function create($field, $table = null) {
		return new self($field, $table);
	}

	public function toDialectString(Dialect $dialect) {
		$field = ($this->table ? $this->table->toDialectString($dialect) . '.' : null) . $dialect->quoteField($this->field);

		return $this->cast ? $dialect->toCasted($field, $this->cast) : $field;
	}

	public function getField() {
		return $this->field;
	}

	/**
	 * @return DialectString
	 **/
	public function getTable() {
		return $this->table;
	}

	/**
	 * @throws WrongStateException
	 * @return DBField
	 **/
	public function setTable($table) {
		if ($this->table !== null) {
			throw new WrongStateException('you should not override setted table');
		}

		if (!$table instanceof DialectString) {
			$this->table = new FromTable($table);
		} else {
			$this->table = $table;
		}

		return $this;
	}
}
