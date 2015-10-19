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
use Hesper\Main\EntityProto\EntityProto;
use Hesper\Main\EntityProto\PrototypedGetter;

/**
 * Class FormGetter
 * @package Hesper\Main\EntityProto\Accessor
 */
final class FormGetter extends PrototypedGetter {

	public function __construct(EntityProto $proto, &$object) {
		Assert::isInstance($object, Form::class);

		return parent::__construct($proto, $object);
	}

	public function get($name) {
		if (!isset($this->mapping[$name])) {
			throw new WrongArgumentException("knows nothing about property '{$name}'");
		}

		$primitive = $this->mapping[$name];

		return $this->object->getValue($primitive->getName());
	}
}
