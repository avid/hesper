<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov, Anton E. Lebedevich
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\Base\Assert;
use Hesper\Core\DB\Dialect;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Class QueryCombination
 * @package Hesper\Core\OSQL
 */
final class QueryCombination extends QueryIdentification implements DialectString {

	private $left  = null;
	private $right = null;
	private $logic = null;

	private $limit  = null;
	private $offset = null;

	private $order = null;

	public function __construct(Query $left, Query $right, $logic) {
		$this->left = $left;
		$this->right = $right;
		$this->logic = $logic;
		$this->order = new OrderChain();
	}

	public function __clone() {
		$this->left = clone $this->left;
		$this->right = clone $this->right;
		$this->order = clone $this->order;
	}

	public function getLimit() {
		return $this->limit;
	}

	public function getOffset() {
		return $this->offset;
	}

	/**
	 * @throws WrongArgumentException
	 * @return QueryCombination
	 **/
	public function limit($limit = null, $offset = null) {
		if ($limit !== null) {
			Assert::isPositiveInteger($limit, 'invalid limit specified');
		}

		if ($offset !== null) {
			Assert::isInteger($offset, 'invalid offset specified');
		}

		$this->limit = $limit;
		$this->offset = $offset;

		return $this;
	}

	/**
	 * @return QueryCombination
	 **/
	public function dropOrder() {
		$this->order = new OrderChain();

		return $this;
	}

	/**
	 * @return QueryCombination
	 **/
	public function setOrderChain(OrderChain $chain) {
		$this->order = $chain;

		return $this;
	}

	/**
	 * @return QueryCombination
	 **/
	public function orderBy($field) {
		$this->order->add($field);

		return $this;
	}

	public function toDialectString(Dialect $dialect) {
		$query = $this->left->toDialectString($dialect) . " {$this->logic} " . $this->right->toDialectString($dialect);

		if ($this->order->getCount()) {
			$query .= ' ORDER BY ' . $this->order->toDialectString($dialect);
		}

		if ($this->limit) {
			$query .= ' LIMIT ' . $this->limit;
		}

		if ($this->offset) {
			$query .= ' OFFSET ' . $this->offset;
		}

		return $query;
	}
}
