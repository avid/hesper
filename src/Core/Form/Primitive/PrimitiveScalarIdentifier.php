<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Exception\WrongStateException;

/**
 * Class PrimitiveScalarIdentifier
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveScalarIdentifier extends PrimitiveIdentifier {

	protected $scalar = true;

	public function setScalar($orly = false) {
		throw new WrongStateException();
	}
}
