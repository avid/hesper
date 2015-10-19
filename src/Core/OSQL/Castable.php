<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov, Anton E. Lebedevich
 */
namespace Hesper\Core\OSQL;

/**
 * Cast-able SQL parts.
 * @package Hesper\Core\OSQL
 */
abstract class Castable implements DialectString {

	protected $cast = null;

	/**
	 * @return Castable
	 **/
	public function castTo($cast) {
		$this->cast = $cast;

		return $this;
	}
}
