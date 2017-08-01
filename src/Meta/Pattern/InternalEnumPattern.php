<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 */
namespace Hesper\Meta\Pattern;

/**
 * Class InternalEnumPattern
 * @package Hesper\Meta\Pattern
 */
class InternalEnumPattern extends InternalCommonPattern {

	public function daoExists() {
		return false;
	}

	public function tableExists() {
		return false;
	}

}
