<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Main\Base;

use Hesper\Core\Form\Primitive\PrimitiveInteger;

/**
 * Integer's set.
 * @ingroup Helpers
 **/
final class IntegerSet extends Range {

	public static function create($min = PrimitiveInteger::SIGNED_MIN, $max = PrimitiveInteger::SIGNED_MAX) {
		return new IntegerSet($min, $max);
	}

	public function contains($value) {
		if ($this->getMin() <= $value && $value <= $this->getMax()) {
			return true;
		} else {
			return false;
		}
	}
}
