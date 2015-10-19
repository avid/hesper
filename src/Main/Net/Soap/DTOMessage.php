<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Net\Soap;

use Hesper\Main\EntityProto\Builder\ObjectToDTOConverter;
use Hesper\Main\EntityProto\PrototypedEntity;

abstract class DTOMessage implements PrototypedEntity {

	final public function makeDto() {
		return ObjectToDTOConverter::create($this->entityProto())->make($this);
	}
}
