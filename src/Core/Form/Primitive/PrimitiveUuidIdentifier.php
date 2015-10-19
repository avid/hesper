<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Exception\UnimplementedFeatureException;

class PrimitiveUuidIdentifier extends PrimitiveIdentifier {

	protected $scalar = true;

	public function setScalar($orly = false) {
		throw new UnimplementedFeatureException();
	}

	public function getTypeName() {
		return 'Uuid';
	}

}
