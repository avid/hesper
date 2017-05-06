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
final class InternalEnumPattern extends InternalCommonPattern {

	public function daoExists() {
		return false;
	}

	public function tableExists() {
		return false;
	}

}
