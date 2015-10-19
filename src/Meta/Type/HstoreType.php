<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Meta\Type;

use Hesper\Main\Base\Hstore;

/**
 * Class HstoreType
 * @package Hesper\Meta\Type
 * @see     http://www.postgresql.org/docs/8.3/interactive/hstore.html
 */
final class HstoreType extends ObjectType {

	public function getPrimitiveName() {
		return 'hstore';
	}

	public function getFullClass() {
		return Hstore::class;
	}

	public function isGeneric() {
		return true;
	}

	public function isMeasurable() {
		return true;
	}

	public function getDeclaration() {
		if ($this->hasDefault()) {
			return "'{$this->default}'";
		}

		return 'null';
	}

	public function toColumnType() {
		return '\Hesper\Core\OSQL\DataType::hstore()';
	}
}
