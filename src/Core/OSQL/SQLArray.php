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
 * Values row implementation.
 * @package Hesper\Core\OSQL
 */
final class SQLArray implements DialectString {

	private $array = [];

	/**
	 * @return SQLArray
	 **/
	public static function create($array) {
		return new self($array);
	}

	public function __construct($array) {
		$this->array = $array;
	}

	public function getArray() {
		return $this->array;
	}

	public function toDialectString(Dialect $dialect) {
		$array = $this->array;

		if (is_array($array)) {
			$quoted = [];

			foreach ($array as $item) {
				if ($item instanceof DialectString) {
					$quoted[] = $item->toDialectString($dialect);
				} else {
					$quoted[] = $dialect->valueToString($item);
				}
			}

			$value = implode(', ', $quoted);
		} else {
			$value = $dialect->quoteValue($array);
		}

		return "({$value})";
	}
}
