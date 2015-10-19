<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Accessor;

use Hesper\Core\Base\Assert;
use Hesper\Core\Form\Form;
use Hesper\Main\EntityProto\EntityProto;
use Hesper\Main\EntityProto\PrototypedSetter;

/**
 * Class FormMutator
 * @package Hesper\Main\EntityProto\Accessor
 */
abstract class FormMutator extends PrototypedSetter {

	private $getter = null;

	public function __construct(EntityProto $proto, &$object) {
		Assert::isInstance($object, Form::class);

		return parent::__construct($proto, $object);
	}

	/**
	 * @return FormGetter
	 **/
	public function getGetter() {
		if (!$this->getter) {
			$this->getter = new FormGetter($this->proto, $this->object);
		}

		return $this->getter;
	}
}
