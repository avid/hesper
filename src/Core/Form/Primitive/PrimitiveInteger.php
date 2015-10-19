<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov, Anton E. Lebedevich
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Assert;

/**
 * @ingroup Primitives
 **/
class PrimitiveInteger extends PrimitiveNumber {

	const SIGNED_SMALL_MIN = -32768;
	const SIGNED_SMALL_MAX = +32767;

	const SIGNED_MIN = -2147483648;
	const SIGNED_MAX = +2147483647;

	const SIGNED_BIG_MIN = -9223372036854775808;
	const SIGNED_BIG_MAX = 9223372036854775807;

	const UNSIGNED_SMALL_MAX = 65535;
	const UNSIGNED_MAX       = 4294967295;

	protected function checkNumber($number) {
		Assert::isInteger($number);
	}

	protected function castNumber($number) {
		return (int)$number;
	}
}
