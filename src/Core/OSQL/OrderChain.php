<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\DB\Dialect;
use Hesper\Core\Logic\MappableObject;
use Hesper\Main\DAO\ProtoDAO;

/**
 * Class OrderChain
 * @package Hesper\Core\OSQL
 */
final class OrderChain implements DialectString, MappableObject {

	private $chain = [];

	/**
	 * @return OrderChain
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return OrderChain
	 **/
	public function add($order) {
		$this->chain[] = $this->makeOrder($order);

		return $this;
	}

	/**
	 * @return OrderChain
	 **/
	public function prepend($order) {
		if ($this->chain) {
			array_unshift($this->chain, $this->makeOrder($order));
		} else {
			$this->chain[] = $this->makeOrder($order);
		}

		return $this;
	}

	/**
	 * @return OrderBy
	 **/
	public function getLast() {
		return end($this->chain);
	}

	public function getList() {
		return $this->chain;
	}

	public function getCount() {
		return count($this->chain);
	}

	/**
	 * @return OrderChain
	 **/
	public function toMapped(ProtoDAO $dao, JoinCapableQuery $query) {
		$chain = new self;

		foreach ($this->chain as $order) {
			$chain->add($order->toMapped($dao, $query));
		}

		return $chain;
	}

	public function toDialectString(Dialect $dialect) {
		if (!$this->chain) {
			return null;
		}

		$out = null;

		foreach ($this->chain as $order) {
			$out .= $order->toDialectString($dialect) . ', ';
		}

		return rtrim($out, ', ');
	}

	/**
	 * @return OrderBy
	 **/
	private function makeOrder($object) {
		if ($object instanceof OrderBy) {
			return $object;
		} elseif ($object instanceof DialectString) {
			return new OrderBy($object);
		}

		return new OrderBy(new DBField($object));
	}
}
