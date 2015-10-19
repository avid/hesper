<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\DB\Transaction;

use Hesper\Core\DB\DB;
use Hesper\Core\Exception\DatabaseException;
use Hesper\Core\Exception\WrongStateException;
use Hesper\Core\OSQL\Query;

/**
 * Database transaction implementation.
 * @package Hesper\Core\DB\Transaction
 */
final class DBTransaction extends BaseTransaction {

	private $started = false;

	public function __destruct() {
		if ($this->isStarted()) {
			$this->db->queryRaw("rollback;\n");
		}
	}

	/**
	 * @return DBTransaction
	 **/
	public function setDB(DB $db) {
		if ($this->isStarted()) {
			throw new WrongStateException('transaction already started, can not switch to another db');
		}

		return parent::setDB($db);
	}

	public function isStarted() {
		return $this->started;
	}

	/**
	 * @return DBTransaction
	 **/
	public function add(Query $query) {
		if (!$this->isStarted()) {
			$this->db->queryRaw($this->getBeginString());
			$this->started = true;
		}

		$this->db->queryNull($query);

		return $this;
	}

	/**
	 * @return DBTransaction
	 **/
	public function flush() {
		$this->started = false;

		try {
			$this->db->queryRaw("commit;\n");
		} catch (DatabaseException $e) {
			$this->db->queryRaw("rollback;\n");
			throw $e;
		}

		return $this;
	}
}
