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
 * Class ValueObjectPattern
 * @package Hesper\Meta\Pattern
 */
class ValueObjectPattern extends AbstractClassPattern {

	public function tableExists() {
		return false;
	}

	/**
	 * @return ValueObjectPattern
	 **/
	protected function fullBuild(MetaClass $class) {
		return $this->buildBusiness($class)
		            ->buildProto($class);
	}
}
