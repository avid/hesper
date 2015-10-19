<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Builder;

use Hesper\Main\EntityProto\Accessor\FormSetter;
use Hesper\Main\EntityProto\Accessor\ObjectGetter;
use Hesper\Main\EntityProto\EntityProto;

/**
 * Class ObjectToFormConverter
 * @package Hesper\Main\EntityProto\Builder
 */
final class ObjectToFormConverter extends FormBuilder {

	/**
	 * @return ObjectToFormConverter
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
		return new FormSetter($this->proto, $object);
	}
}
