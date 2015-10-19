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
 * Class FieldTable
 * @package Hesper\Core\OSQL
 */
abstract class FieldTable extends Castable {

	protected $field = null;

	public function __construct($field) {
		$this->field = $field;
	}

	public function getField() {
		return $this->field;
	}

	public function toDialectString(Dialect $dialect) {
		$out = $dialect->fieldToString($this->field);

		return $this->cast ? $dialect->toCasted($out, $this->cast) : $out;
	}
}
