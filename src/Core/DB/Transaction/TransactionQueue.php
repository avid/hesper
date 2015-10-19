<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\DB\Transaction;

use Hesper\Core\DB\DB;
use Hesper\Core\DB\Dialect;
use Hesper\Core\DB\ImaginaryDialect;
use Hesper\Core\DB\Queue;
use Hesper\Core\Exception\DatabaseException;
use Hesper\Core\Exception\UnsupportedMethodException;
use Hesper\Core\OSQL\Query;

/**
 * Transaction-wrapped queries queue.
 * @see     Queue
 * @package Hesper\Core\DB\Transaction
 */
final class TransactionQueue extends BaseTransaction implements Query {

	private $queue = null;

	public function __construct(DB $db) {
		parent::__construct($db);
		$this->queue = new Queue();
	}

	public function getId() {
		return sha1(serialize($this));
	}

	public function setId($id) {
		throw new UnsupportedMethodException();
	}

	/**
	 * @return TransactionQueue
	 **/
	public function add(Query $query) {
		$this->queue->add($query);

		return $this;
	}

	/**
	 * @throws DatabaseException
	 * @return TransactionQueue
	 **/
	public function flush() {
		try {
			$this->db->queryRaw($this->getBeginString());
			$this->queue->flush($this->db);
			$this->db->queryRaw("commit;\n");
		} catch (DatabaseException $e) {
			$this->db->queryRaw("rollback;\n");
			throw $e;
		}

		return $this;
	}

	// to satisfy Query interface
	public function toDialectString(Dialect $dialect) {
		return $this->queue->toDialectString($dialect);
	}

	public function toString() {
		return $this->queue->toDialectString(ImaginaryDialect::me());
	}
}
