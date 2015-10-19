<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Accessor;

use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Main\EntityProto\PrototypedGetter;

/**
 * Class ScopeGetter
 * @package Hesper\Main\EntityProto\Accessor
 */
final class ScopeGetter extends PrototypedGetter {

	public function get($name) {
		if (!isset($this->mapping[$name])) {
			throw new WrongArgumentException("knows nothing about property '{$name}'");
		}

		$primitive = $this->mapping[$name];

		$key = $primitive->getName();

		return isset($this->object[$key]) ? $this->object[$key] : null;
	}
}
