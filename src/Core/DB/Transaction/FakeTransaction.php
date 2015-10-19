<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\DB\Transaction;

use Hesper\Core\OSQL\Query;

/**
 * Transaction-like wrapper around DB's queryNull.
 * @package Hesper\Core\DB\Transaction
 */
final class FakeTransaction extends BaseTransaction {

	/**
	 * @return FakeTransaction
	 **/
	public function add(Query $query) {
		$this->db->queryNull($query);

		return $this;
	}

	/**
	 * @return FakeTransaction
	 **/
	public function flush() {
		return $this;
	}
}
