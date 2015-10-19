<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich, Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\DB\Dialect;
use Hesper\Core\Logic\LogicalObject;
use Hesper\Core\Logic\MappableObject;
use Hesper\Main\DAO\ProtoDAO;

/**
 * Class GroupBy
 * @package Hesper\Core\OSQL
 */
final class GroupBy extends FieldTable implements MappableObject {

	/**
	 * @return GroupBy
	 **/
	public static function create($field) {
		return new self($field);
	}

	/**
	 * @return GroupBy
	 **/
	public function toMapped(ProtoDAO $dao, JoinCapableQuery $query) {
		return self::create($dao->guessAtom($this->field, $query));
	}

	public function toDialectString(Dialect $dialect) {
		if ($this->field instanceof SelectQuery || $this->field instanceof LogicalObject) {
			return '(' . $dialect->fieldToString($this->field) . ')';
		} else {
			return parent::toDialectString($dialect);
		}
	}
}
