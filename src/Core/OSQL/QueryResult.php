<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\Base\Identifiable;
use Hesper\Core\Exception\UnsupportedMethodException;

/**
 * Holder for query's execution information.
 * @package Hesper\Core\OSQL
 */
final class QueryResult implements Identifiable {

	private $list = [];

	private $count    = 0;
	private $affected = 0;

	private $query = null;

	/**
	 * @return QueryResult
	 **/
	public static function create() {
		return new self;
	}

	public function getId() {
		return '_result_' . $this->query->getId();
	}

	public function setId($id) {
		throw new UnsupportedMethodException();
	}

	/**
	 * @return SelectQuery
	 **/
	public function getQuery() {
		return $this->query;
	}

	/**
	 * @return QueryResult
	 **/
	public function setQuery(SelectQuery $query) {
		$this->query = $query;

		return $this;
	}

	public function getList() {
		return $this->list;
	}

	/**
	 * @return QueryResult
	 **/
	public function setList($list) {
		$this->list = $list;

		return $this;
	}

	public function getCount() {
		return $this->count;
	}

	/**
	 * @return QueryResult
	 **/
	public function setCount($count) {
		$this->count = $count;

		return $this;
	}

	public function getAffected() {
		return $this->affected;
	}

	/**
	 * @return QueryResult
	 **/
	public function setAffected($affected) {
		$this->affected = $affected;

		return $this;
	}
}
