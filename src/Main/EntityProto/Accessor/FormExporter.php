<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Accessor;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Form\Form;
use Hesper\Core\Form\Primitive\PrimitiveForm;
use Hesper\Main\EntityProto\EntityProto;
use Hesper\Main\EntityProto\PrototypedGetter;

/**
 * Class FormExporter
 * @package Hesper\Main\EntityProto\Accessor
 */
final class FormExporter extends PrototypedGetter {

	public function __construct(EntityProto $proto, $object) {
		Assert::isInstance($object, Form::class);

		return parent::__construct($proto, $object);
	}

	public function get($name) {
		if (!isset($this->mapping[$name])) {
			throw new WrongArgumentException("knows nothing about property '{$name}'");
		}

		$primitive = $this->mapping[$name];

		$formPrimitive = $this->object->get($primitive->getName());

		if ($primitive instanceof PrimitiveForm) {
			// export of inner forms controlled by builder
			return $formPrimitive->getValue();
		}

		return $formPrimitive->exportValue();
	}
}
