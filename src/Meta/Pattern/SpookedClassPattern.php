<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Pattern;

use Hesper\Core\Base\Singleton;
use Hesper\Meta\Entity\MetaClass;

/**
 * Class SpookedClassPattern
 * @package Hesper\Meta\Pattern
 */
class SpookedClassPattern extends Singleton implements GenerationPattern {

	public function build(MetaClass $class) {
		return $this;
	}

	public function daoExists() {
		return false;
	}

	public function tableExists() {
		return false;
	}

}
