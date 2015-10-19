<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Builder;

use Hesper\Main\EntityProto\Accessor\FormHardenedSetter;
use Hesper\Main\EntityProto\Accessor\FormSetter;
use Hesper\Main\EntityProto\Accessor\ObjectGetter;
use Hesper\Main\EntityProto\EntityProto;

final class ObjectToFormSetter extends FormBuilder {

	/**
	 * @return ObjectToFormSetter
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
	 * @return FormSetter
	 **/
	protected function getSetter(&$object) {
		return new FormHardenedSetter($this->proto, $object);
	}
}
