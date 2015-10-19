<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\DB\Dialect;

/**
 * Container for passing binary values into OSQL queries.
 * @ingroup OSQL
 * @ingroup Module
 **/
final class DBBinary extends DBValue {

	/**
	 * @return DBBinary
	 **/
	public static function create($value) {
		return new self($value);
	}

	public function toDialectString(Dialect $dialect) {
		return $dialect->quoteBinary($this->getValue());
	}
}
