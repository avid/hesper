<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Pattern;

/**
 * Class StraightMappingPattern
 * @package Hesper\Meta\Pattern
 */
final class StraightMappingPattern extends BasePattern {

	public function daoExists() {
		return true;
	}
}
