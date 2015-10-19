<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Builder;

use Hesper\Main\EntityProto\Accessor\DTOSetter;
use Hesper\Main\EntityProto\Accessor\ObjectGetter;
use Hesper\Main\EntityProto\EntityProto;

/**
 * Class ObjectToDTOConverter
 * @package Hesper\Main\EntityProto\Builder
 */
final class ObjectToDTOConverter extends DTOBuilder {

	/**
	 * @return ObjectToDTOConverter
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
	 * @return DTOSetter
	 **/
	protected function getSetter(&$object) {
		return new DTOSetter($this->proto, $object);
	}
}
