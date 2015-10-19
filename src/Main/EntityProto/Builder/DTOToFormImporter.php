<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Builder;

use Hesper\Main\EntityProto\Accessor\DTOGetter;
use Hesper\Main\EntityProto\Accessor\FormImporter;
use Hesper\Main\EntityProto\EntityProto;

/**
 * Class DTOToFormImporter
 * @package Hesper\Main\EntityProto\Builder
 */
final class DTOToFormImporter extends FormBuilder {

	/**
	 * @return DTOToFormImporter
	 **/
	public static function create(EntityProto $proto) {
		return new self($proto);
	}

	/**
	 * @return FormImporter
	 **/
	protected function getGetter($object) {
		return new DTOGetter($this->proto, $object);
	}

	/**
	 * @return FormImporter
	 **/
	protected function getSetter(&$object) {
		return new FormImporter($this->proto, $object);
	}
}
