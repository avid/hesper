<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Builder;

use Hesper\Main\EntityProto\PrototypedBuilder;

/**
 * Class ObjectBuilder
 * @package Hesper\Main\EntityProto\Accessor
 */
abstract class ObjectBuilder extends PrototypedBuilder {

	protected function createEmpty() {
		return $this->proto->createObject();
	}
}
