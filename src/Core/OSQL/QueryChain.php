<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov, Anton E. Lebedevich
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\Exception\UnsupportedMethodException;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Form\Form;

/**
 * Class QueryChain
 * @package Hesper\Core\OSQL
 */
final class QueryChain extends SQLChain {

	public function toBoolean(Form $form) {
		throw new UnsupportedMethodException('get rid of useless interface');
	}

	/**
	 * @return QueryChain
	 **/
	public static function block($args, $logic) {
		$queryChain = new self;

		foreach ($args as $arg) {
			if (!$arg instanceof SelectQuery) {
				throw new WrongArgumentException('unsupported object type: ' . get_class($arg));
			}

			$queryChain->exp($arg, $logic);
		}

		return $queryChain;
	}

	/**
	 * @return QueryChain
	 **/
	public function union(SelectQuery $query) {
		return $this->exp($query, CombineQuery::UNION);
	}

	/**
	 * @return QueryChain
	 **/
	public function unionAll(SelectQuery $query) {
		return $this->exp($query, CombineQuery::UNION_ALL);
	}

	/**
	 * @return QueryChain
	 **/
	public function intersect(SelectQuery $query) {
		return $this->exp($query, CombineQuery::INTERSECT);
	}

	/**
	 * @return QueryChain
	 **/
	public function intersectAll(SelectQuery $query) {
		return $this->exp($query, CombineQuery::INTERSECT_ALL);
	}

	/**
	 * @return QueryChain
	 **/
	public function except(SelectQuery $query) {
		return $this->exp($query, CombineQuery::EXCEPT);
	}

	/**
	 * @return QueryChain
	 **/
	public function exceptAll(SelectQuery $query) {
		return $this->exp($query, CombineQuery::EXCEPT_ALL);
	}
}
