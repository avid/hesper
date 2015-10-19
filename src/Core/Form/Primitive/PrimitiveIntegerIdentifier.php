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
 * Class PrimitiveIntegerIdentifier
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveIntegerIdentifier extends PrimitiveIdentifier {

	protected $scalar = false;

	public function setScalar($orly = false) {
		throw new WrongStateException();
	}
}
