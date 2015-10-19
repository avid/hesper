<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Identifiable;
use Hesper\Core\Base\Stringable;
use Hesper\Core\Base\Time;
use Hesper\Core\DB\Dialect;
use Hesper\Core\Exception\MissingElementException;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Main\Base\DateRange;
use Hesper\Main\Base\Range;

/**
 * Single roof for InsertQuery and UpdateQuery.
 * @package Hesper\Core\OSQL
 */
abstract class InsertOrUpdateQuery extends QuerySkeleton implements SQLTableName {

	protected $table  = null;
	protected $fields = [];

	abstract public function setTable($table);

	public function getTable() {
		return $this->table;
	}

	public function getFieldsCount() {
		return count($this->fields);
	}

	/**
	 * @return InsertOrUpdateQuery
	 **/
	public function set($field, $value = null) {
		$this->fields[$field] = $value;

		return $this;
	}

	/**
	 * @throws MissingElementException
	 * @return InsertOrUpdateQuery
	 **/
	public function drop($field) {
		if (!array_key_exists($field, $this->fields)) {
			throw new MissingElementException("unknown field '{$field}'");
		}

		unset($this->fields[$field]);

		return $this;
	}

	/**
	 * @return InsertOrUpdateQuery
	 **/
	public function lazySet($field, /* Identifiable */
	                        $object = null) {
		if ($object === null) {
			$this->set($field, null);
		} elseif ($object instanceof Identifiable) {
			$this->set($field, $object->getId());
		} elseif ($object instanceof Range) {
			$this->set($field . '_min', $object->getMin())
			     ->set($field . '_max', $object->getMax());
		} elseif ($object instanceof DateRange) {
			$this->set($field . '_start', $object->getStart())
			     ->set($field . '_end', $object->getEnd());
		} elseif ($object instanceof Time) {
			$this->set($field, $object->toFullString());
		} elseif ($object instanceof Stringable) {
			$this->set($field, $object->toString());
		} else {
			$this->set($field, $object);
		}

		return $this;
	}

	/**
	 * @return InsertOrUpdateQuery
	 **/
	public function setBoolean($field, $value = false) {
		try {
			Assert::isTernaryBase($value);
			$this->set($field, $value);
		} catch (WrongArgumentException $e) {/*_*/
		}

		return $this;
	}

	/**
	 * Adds values from associative array.
	 * @return InsertOrUpdateQuery
	 **/
	public function arraySet($fields) {
		Assert::isArray($fields);

		$this->fields = array_merge($this->fields, $fields);

		return $this;
	}

	public function toDialectString(Dialect $dialect) {
		$this->checkReturning($dialect);

		if (empty($this->returning)) {
			return parent::toDialectString($dialect);
		}

		$query = parent::toDialectString($dialect) . ' RETURNING ' . $this->toDialectStringReturning($dialect);

		return $query;
	}
}
