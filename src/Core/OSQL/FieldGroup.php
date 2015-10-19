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
 * Class FieldGroup
 * @package Hesper\Core\OSQL
 */
final class FieldGroup implements DialectString {

	private $list = [];

	/**
	 * @return FieldGroup
	 **/
	public function add(Castable $field) {
		$this->list[] = $field;

		return $this;
	}

	public function toDialectString(Dialect $dialect) {
		if (!$this->list) {
			return null;
		}

		$out = [];

		foreach ($this->list as $field) {
			$out[] = $field->toDialectString($dialect);
		}

		return implode(', ', $out);
	}
}
