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
 * Class InternalClassPattern
 * @package Hesper\Meta\Pattern
 */
final class InternalClassPattern extends BasePattern implements GenerationPattern {

	/**
	 * @return InternalClassPattern
	 **/
	public function build(MetaClass $class) {
		return $this;
	}

	public function tableExists() {
		return false;
	}

	public function daoExists() {
		return true;
	}
}
