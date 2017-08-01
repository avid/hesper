<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Georgiy T. Kutsurua
 */
namespace Hesper\Meta\Pattern;

use Hesper\Meta\Entity\MetaClass;

/**
 * Class SpookedEnumPattern
 * @package Hesper\Meta\Pattern
 */
class SpookedEnumPattern extends EnumClassPattern {

	/**
	 * @return SpookedEnumPattern
	 **/
	public function build(MetaClass $class) {
		return $this;
	}

	public function daoExists() {
		return false;
	}
}
