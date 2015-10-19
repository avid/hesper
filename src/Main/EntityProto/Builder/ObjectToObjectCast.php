<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Builder;

use Hesper\Main\EntityProto\Accessor\ObjectGetter;
use Hesper\Main\EntityProto\Accessor\ObjectSetter;
use Hesper\Main\EntityProto\EntityProto;

final class ObjectToObjectCast extends ObjectBuilder {

	/**
	 * @return ObjectToObjectCast
	 **/
	public static function create(EntityProto $proto) {
		return new self($proto);
	}

	/**
	 * @return ObjectGetter
	 **/
	protected function getGetter($object) {
		return new ObjectGetter($this->proto, $object);
	}

	/**
	 * @return ObjectSetter
	 **/
	protected function getSetter(&$object) {
		return new ObjectSetter($this->proto, $object);
	}
}
