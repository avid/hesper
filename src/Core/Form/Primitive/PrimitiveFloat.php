<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Assert;

/**
 * Class PrimitiveFloat
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveFloat extends PrimitiveNumber {

	protected function checkNumber($number) {
		Assert::isFloat($number);
	}

	protected function castNumber($number) {
		return (float)$number;
	}
}
