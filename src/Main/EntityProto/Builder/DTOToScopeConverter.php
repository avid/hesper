<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Builder;

use Hesper\Main\EntityProto\Accessor\DTOGetter;
use Hesper\Main\EntityProto\Accessor\ScopeSetter;
use Hesper\Main\EntityProto\EntityProto;
use Hesper\Main\EntityProto\PrototypedBuilder;

/**
 * Class DTOToScopeConverter
 * @package Hesper\Main\EntityProto\Builder
 */
final class DTOToScopeConverter extends PrototypedBuilder {

	/**
	 * @return DTOToScopeConverter
	 **/
	public static function create(EntityProto $proto) {
		return new self($proto);
	}

	protected function createEmpty() {
		return [];
	}

	/**
	 * @return DTOGetter
	 **/
	protected function getGetter($object) {
		return new DTOGetter($this->proto, $object);
	}

	/**
	 * @return ScopeSetter
	 **/
	protected function getSetter(&$object) {
		return new ScopeSetter($this->proto, $object);
	}
}
