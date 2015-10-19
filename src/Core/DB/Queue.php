<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\DB;

use Hesper\Core\Exception\MissingElementException;
use Hesper\Core\Exception\UnsupportedMethodException;
use Hesper\Core\OSQL\Query;

/**
 * OSQL's queries queue.
 * @package Hesper\Core\DB
 * @see     OSQL
 * @todo    introduce DBs without multi-query support handling
 */
final class Queue implements Query {

	private $queue = [];

	/**
	 * @return Queue
	 **/
	public static function create() {
		return new self;
	}

	public function getId() {
		return sha1(serialize($this->queue));
	}

	public function setId($id) {
		throw new UnsupportedMethodException();
	}

	public function getQueue() {
		return $this->queue;
	}

	/**
	 * @return Queue
	 **/
	public function add(Query $query) {
		$this->queue[] = $query;

		return $this;
	}

	/**
	 * @return Queue
	 **/
	public function remove(Query $query) {
		if (!$id = array_search($query, $this->queue)) {
			throw new MissingElementException();
		}

		unset($this->queue[$id]);

		return $this;
	}

	/**
	 * @return Queue
	 **/
	public function drop() {
		$this->queue = [];

		return $this;
	}

	/**
	 * @return Queue
	 **/
	public function run(DB $db) {
		$db->queryRaw($this->toDialectString($db->getDialect()));

		return $this;
	}

	/**
	 * @return Queue
	 **/
	public function flush(DB $db) {
		return $this->run($db)
		            ->drop();
	}

	// to satisfy Query interface
	public function toString() {
		return $this->toDialectString(ImaginaryDialect::me());
	}

	public function toDialectString(Dialect $dialect) {
		$out = [];

		foreach ($this->queue as $query) {
			$out[] = $query->toDialectString($dialect);
		}

		return implode(";\n", $out);
	}
}
