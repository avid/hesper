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
use Hesper\Core\Exception\UnsupportedMethodException;

/**
 * Class TruncateQuery
 * @package Hesper\Core\OSQL
 */
final class TruncateQuery extends QueryIdentification {

	private $targets = [];

	public function __construct($whom = null) {
		if ($whom) {
			if (is_array($whom)) {
				$this->targets = $whom;
			} else {
				$this->targets[] = $whom;
			}
		}
	}

	public function getId() {
		throw new UnsupportedMethodException();
	}

	/**
	 * @return TruncateQuery
	 **/
	public function table($table) {
		if ($table instanceof SQLTableName) {
			$this->targets[] = $table->getTable();
		} else {
			$this->targets[] = $table;
		}

		return $this;
	}

	public function toDialectString(Dialect $dialect) {
		Assert::isTrue(($this->targets !== []), 'do not know who should i truncate');

		if ($dialect->hasTruncate()) {
			$head = 'TRUNCATE TABLE ';
		} else {
			$head = 'DELETE FROM ';
		}

		if ($dialect->hasMultipleTruncate()) {
			$query = $head . $this->dumpTargets($dialect, null, ',');
		} else {
			$query = $this->dumpTargets($dialect, $head, ';');
		}

		return $query . ';';
	}

	private function dumpTargets(Dialect $dialect, $prepend = null, $append = null) {
		if (count($this->targets) == 1) {
			return $prepend . $dialect->quoteTable(reset($this->targets));
		} else {
			$tables = [];

			foreach ($this->targets as $target) {
				if ($target instanceof DialectString) {
					$table = $dialect->quoteTable($target->toDialectString($dialect));
				} else {
					$table = $dialect->quoteTable($target);
				}

				$tables[] = $prepend . $table;
			}

			return implode($append . ' ', $tables);
		}
	}
}
