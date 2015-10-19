<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\Base\Assert;
use Hesper\Core\DB\Dialect;
use Hesper\Core\Exception\MissingElementException;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Class DBSchema
 * @package Hesper\Core\OSQL
 */
final class DBSchema extends QueryIdentification {

	private $tables = [];
	private $order  = [];

	public function getTables() {
		return $this->tables;
	}

	public function getTableNames() {
		return $this->order;
	}

	/**
	 * @throws WrongArgumentException
	 * @return DBSchema
	 **/
	public function addTable(DBTable $table) {
		$name = $table->getName();

		Assert::isFalse(isset($this->tables[$name]), "table '{$name}' already exist");

		$this->tables[$table->getName()] = $table;
		$this->order[] = $name;

		return $this;
	}

	/**
	 * @throws MissingElementException
	 * @return DBTable
	 **/
	public function getTableByName($name) {
		if (!isset($this->tables[$name])) {
			throw new MissingElementException("table '{$name}' does not exist");
		}

		return $this->tables[$name];
	}

	public function toDialectString(Dialect $dialect) {
		$out = [];

		foreach ($this->order as $name) {
			$out[] = $this->tables[$name]->toDialectString($dialect);
		}

		return implode("\n\n", $out);
	}
}
