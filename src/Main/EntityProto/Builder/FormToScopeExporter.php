<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Builder;

use Hesper\Main\EntityProto\Accessor\FormExporter;
use Hesper\Main\EntityProto\Accessor\FormGetter;
use Hesper\Main\EntityProto\Accessor\ObjectSetter;
use Hesper\Main\EntityProto\Accessor\ScopeSetter;
use Hesper\Main\EntityProto\EntityProto;

/**
 * Class FormToScopeExporter
 * @package Hesper\Main\EntityProto\Builder
 */
final class FormToScopeExporter extends ObjectBuilder {

	/**
	 * @return FormToObjectConverter
	 **/
	public static function create(EntityProto $proto) {
		return new self($proto);
	}

	protected function createEmpty() {
		return [];
	}

	/**
	 * @return FormGetter
	 **/
	protected function getGetter($object) {
		return new FormExporter($this->proto, $object);
	}

	/**
	 * @return ObjectSetter
	 **/
	protected function getSetter(&$object) {
		return new ScopeSetter($this->proto, $object);
	}
}
