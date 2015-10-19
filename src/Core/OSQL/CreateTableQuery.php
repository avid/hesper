<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\DB\Dialect;

/**
 * @ingroup OSQL
 **/
final class CreateTableQuery extends QueryIdentification {

	private $table = null;

	public function __construct(DBTable $table) {
		$this->table = $table;
	}

	public function toDialectString(Dialect $dialect) {
		$name = $this->table->getName();

		$middle = "CREATE TABLE {$dialect->quoteTable($name)} (\n    ";

		$prepend = [];
		$columns = [];
		$primary = [];

		$order = $this->table->getOrder();

		foreach ($order as $column) {

			if ($column->isAutoincrement()) {

				if ($pre = $dialect->preAutoincrement($column)) {
					$prepend[] = $pre;
				}

				$columns[] = implode(' ', [$column->toDialectString($dialect), $dialect->postAutoincrement($column)]);
			} else {
				$columns[] = $column->toDialectString($dialect);
			}

			$name = $column->getName();

			if ($column->isPrimaryKey()) {
				$primary[] = $dialect->quoteField($name);
			}
		}

		$out = ($prepend ? implode("\n", $prepend) . "\n" : null) . $middle . implode(",\n    ", $columns);

		if ($primary) {
			$out .= ",\n    PRIMARY KEY(" . implode(', ', $primary) . ')';
		}

		if ($uniques = $this->table->getUniques()) {
			$names = [];

			foreach ($uniques as $row) {
				foreach ($row as $name) {
					$names[] = $dialect->quoteField($name);
				}

				$out .= ",\n    UNIQUE(" . implode(', ', $names) . ')';
			}
		}

		return $out . "\n);\n";
	}
}
