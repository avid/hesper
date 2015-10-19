<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\DB\Dialect;

/**
 * Container for passing values into OSQL queries.
 * @package Hesper\Core\OSQL
 */
class DBValue extends Castable {

	private $value = null;

	/**
	 * @return DBValue
	 **/
	public static function create($value) {
		return new self($value);
	}

	public function __construct($value) {
		$this->value = $value;
	}

	public function getValue() {
		return $this->value;
	}

	public function toDialectString(Dialect $dialect) {
		$out = $dialect->quoteValue($this->value);

		return $this->cast ? $dialect->toCasted($out, $this->cast) : $out;
	}
}
