<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Accessor;

use Hesper\Main\EntityProto\PrototypedGetter;

/**
 * Class ObjectGetter
 * @package Hesper\Main\EntityProto\Accessor
 */
final class ObjectGetter extends PrototypedGetter {

	public function get($name) {
		$method = 'get' . ucfirst($name);

		return $this->object->$method();
	}
}
