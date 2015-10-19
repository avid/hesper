<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\DB\ImaginaryDialect;
use Hesper\Core\Exception\UnsupportedMethodException;

/**
 * Class QueryIdentification
 * @package Hesper\Core\OSQL
 */
abstract class QueryIdentification implements Query {

	public function getId() {
		return sha1($this->toString());
	}

	final public function setId($id) {
		throw new UnsupportedMethodException();
	}

	public function toString() {
		return $this->toDialectString(ImaginaryDialect::me());
	}
}
