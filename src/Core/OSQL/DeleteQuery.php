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
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Class DeleteQuery
 * @package Hesper\Core\OSQL
 */
final class DeleteQuery extends QuerySkeleton implements SQLTableName {

	protected $table = null;

	public function getId() {
		throw new UnsupportedMethodException();
	}

	/**
	 * @return DeleteQuery
	 **/
	public function from($table) {
		$this->table = $table;

		return $this;
	}

	public function getTable() {
		return $this->table;
	}

	public function toDialectString(Dialect $dialect) {
		if ($this->where) {
			$deleteStr = 'DELETE FROM ' . $dialect->quoteTable($this->table) . parent::toDialectString($dialect);

			$this->checkReturning($dialect);

			if (empty($this->returning)) {
				return $deleteStr;
			} else {
				$query = $deleteStr . ' RETURNING ' . $this->toDialectStringReturning($dialect);

				return $query;
			}
		} else {
			throw new WrongArgumentException("leave '{$this->table}' table alone in peace, bastard");
		}
	}
}
