<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\DB;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Identifier;
use Hesper\Core\OSQL\Query;

/**
 * Workaround for sequenceless DB's.
 * You should follow two conventions, when stornig objects thru this one:
 * 1) objects should be childs of IdentifiableObject;
 * 2) sequence name should equal table name + '_id'.
 * @package Hesper\Core\DB
 * @see     IdentifiableOjbect
 * @see     MySQL
 * @see     SQLite
 */
abstract class Sequenceless extends DB {

	protected $sequencePool = [];

	abstract protected function getInsertId();

	/**
	 * @return Identifier
	 **/
	final public function obtainSequence($sequence) {
		$id = Identifier::create();

		$this->sequencePool[$sequence][] = $id;

		return $id;
	}

	final public function query(Query $query) {
		$result = $this->queryRaw($query->toDialectString($this->getDialect()));

		if (($query instanceof InsertQuery) && !empty($this->sequencePool[$name = $query->getTable() . '_id'])) {
			$id = current($this->sequencePool[$name]);

			Assert::isTrue($id instanceof Identifier, 'identifier was lost in the way');

			$id->setId($this->getInsertId())
			   ->finalize();

			unset($this->sequencePool[$name][key($this->sequencePool[$name])]);
		}

		return $result;
	}
}
