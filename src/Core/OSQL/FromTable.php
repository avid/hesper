<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\Base\Aliased;
use Hesper\Core\DB\Dialect;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Logic\LogicalObject;

/**
 * SQL's "FROM"-member implementation.
 * @package Hesper\Core\OSQL
 */
final class FromTable implements Aliased, SQLTableName, SQLRealTableName {

	private $table  = null;
	private $alias  = null;
	private $schema = null;

	public function __construct($table, $alias = null) {
		if (!$alias && ($table instanceof SelectQuery || $table instanceof LogicalObject || $table instanceof SQLFunction)) {
			throw new WrongArgumentException('you should specify alias, when using ' . 'SelectQuery or LogicalObject as table');
		}

		if (is_string($table) && strpos($table, '.') !== false) {
			list($this->schema, $this->table) = explode('.', $table, 2);
		} else {
			$this->table = $table;
		}

		$this->alias = $alias;
	}

	public function getAlias() {
		return $this->alias;
	}

	public function toDialectString(Dialect $dialect) {
		if ($this->table instanceof Query || ($this->table instanceof SQLChain && $this->table->getSize() === 1)) {
			return "({$this->table->toDialectString($dialect)}) AS " . $dialect->quoteTable($this->alias);
		} elseif ($this->table instanceof DialectString) {
			return $this->table->toDialectString($dialect) . ' AS ' . $dialect->quoteTable($this->alias);
		} else {
			return ($this->schema ? $dialect->quoteTable($this->schema) . "." : null) . $dialect->quoteTable($this->table) . ($this->alias ? ' AS ' . $dialect->quoteTable($this->alias) : null);
		}
	}

	public function getTable() {
		return $this->alias ? $this->alias : $this->table;
	}

	public function getRealTable() {
		return $this->table;
	}
}
