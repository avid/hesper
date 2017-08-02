<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich, Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\Base\Assert;
use Hesper\Core\DB\Dialect;
use Hesper\Core\Logic\LogicalObject;
use Hesper\Core\Logic\MappableObject;
use Hesper\Main\DAO\ProtoDAO;

/**
 * Class SQLChain
 * @package Hesper\Core\OSQL
 */
abstract class SQLChain implements LogicalObject, MappableObject {

	protected $chain = [];
	protected $logic = [];

	/**
	 * @param DialectString $exp
	 * @param               $logic
	 *
	 * @return $this
	 */
	protected function exp(DialectString $exp, $logic) {
		$this->chain[] = $exp;
		$this->logic[] = $logic;

		return $this;
	}

	public function getSize() {
		return count($this->chain);
	}

	public function getChain() {
		return $this->chain;
	}

	public function getLogic() {
		return $this->logic;
	}

	/**
	 * @param ProtoDAO         $dao
	 * @param JoinCapableQuery $query
	 *
	 * @return static
	 */
	public function toMapped(ProtoDAO $dao, JoinCapableQuery $query) {
		$size = count($this->chain);

		Assert::isTrue($size > 0, 'empty chain');

		$chain = new static();

		for ($i = 0; $i < $size; ++$i) {
			$chain->exp($dao->guessAtom($this->chain[$i], $query), $this->logic[$i]);
		}

		return $chain;
	}

	public function toDialectString(Dialect $dialect) {
		if ($this->chain) {
			$out = $this->chain[0]->toDialectString($dialect) . ' ';
			for ($i = 1, $size = count($this->chain); $i < $size; ++$i) {
				$out .= $this->logic[$i] . ' ' . $this->chain[$i]->toDialectString($dialect) . ' ';
			}

			if ($size > 1) {
				$out = rtrim($out);
			} // trailing space

			if ($size === 1) {
				return $out;
			}

			return '(' . $out . ')';
		}

		return null;
	}
}
