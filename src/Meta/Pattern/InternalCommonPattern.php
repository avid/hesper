<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 */
namespace Hesper\Meta\Pattern;

use Hesper\Meta\Entity\MetaClass;

/**
 * Class InternalEnumPattern
 * @package Hesper\Meta\Pattern
 */
abstract class InternalCommonPattern extends BasePattern {

	/**
	 * @param MetaClass $class
	 *
	 * @return $this
	 */
	public function build(MetaClass $class) {
		return $this;
	}

	public function tableExists() {
		return false;
	}

}
