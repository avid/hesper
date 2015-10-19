<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Type;

/**
 * Class InternalType
 * @package Hesper\Meta\Type
 */
abstract class InternalType extends ObjectType {

	public function isGeneric() {
		return true;
	}
}
