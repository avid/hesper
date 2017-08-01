<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Pattern;

use Hesper\Meta\Entity\MetaClass;

/**
 * Class SpookedEnumerationPattern
 * @package Hesper\Meta\Pattern
 */
class SpookedEnumerationPattern extends EnumerationClassPattern {

	/**
	 * @return SpookedEnumerationPattern
	 **/
	public function build(MetaClass $class) {
		return $this;
	}

	public function daoExists() {
		return false;
	}
}
