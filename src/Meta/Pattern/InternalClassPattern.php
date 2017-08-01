<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Pattern;

/**
 * Class InternalClassPattern
 * @package Hesper\Meta\Pattern
 */
class InternalClassPattern extends InternalCommonPattern implements GenerationPattern {

	public function daoExists() {
		return true;
	}
}
