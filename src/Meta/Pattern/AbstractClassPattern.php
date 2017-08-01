<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Pattern;

/**
 * Class AbstractClassPattern
 * @package Hesper\Meta\Pattern
 */
class AbstractClassPattern extends BasePattern {

	public function tableExists() {
		return false;
	}
}
