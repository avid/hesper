<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Type;

use Hesper\Main\Base\Range;

/**
 * Class RangeType
 * @package Hesper\Meta\Type
 */
class RangeType extends InternalType {

	public function getPrimitiveName() {
		return 'range';
	}

	public function getFullClass() {
		return Range::class;
	}

	public function toColumnType() {
		return null;
	}
}
