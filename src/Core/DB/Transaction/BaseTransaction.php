<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\DB\Transaction;

use Hesper\Core\DB\DB;

/**
 * Transaction's basis.
 * @package Hesper\Core\DB\Transaction
 */
abstract class BaseTransaction {

	protected $db = null;

	protected $isoLevel = null;
	protected $mode     = null;

	abstract public function flush();

	public function __construct(DB $db) {
		$this->db = $db;
	}

	/**
	 * @return BaseTransaction
	 **/
	public function setDB(DB $db) {
		$this->db = $db;

		return $this;
	}

	/**
	 * @return DB
	 **/
	public function getDB() {
		return $this->db;
	}

	/**
	 * @return BaseTransaction
	 **/
	public function setIsolationLevel(IsolationLevel $level) {
		$this->isoLevel = $level;

		return $this;
	}

	/**
	 * @return BaseTransaction
	 **/
	public function setAccessMode(AccessMode $mode) {
		$this->mode = $mode;

		return $this;
	}

	protected function getBeginString() {
		$begin = 'start transaction';

		if ($this->isoLevel) {
			$begin .= ' ' . $this->isoLevel->toString();
		}

		if ($this->mode) {
			$begin .= ' ' . $this->mode->toString();
		}

		return $begin . ";\n";
	}
}
