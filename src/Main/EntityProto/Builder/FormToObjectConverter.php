<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Builder;

use Hesper\Main\EntityProto\Accessor\FormGetter;
use Hesper\Main\EntityProto\Accessor\ObjectSetter;
use Hesper\Main\EntityProto\EntityProto;

/**
 * Class FormToObjectConverter
 * @package Hesper\Main\EntityProto\Accessor
 */
final class FormToObjectConverter extends ObjectBuilder {

	/**
	 * @return FormToObjectConverter
	 **/
	public static function create(EntityProto $proto) {
		return new self($proto);
	}

	/**
	 * @return FormGetter
	 **/
	protected function getGetter($object) {
		return new FormGetter($this->proto, $object);
	}

	/**
	 * @return ObjectSetter
	 **/
	protected function getSetter(&$object) {
		return new ObjectSetter($this->proto, $object);
	}
}
