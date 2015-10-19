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
 * Class DTOBuilder
 * @package Hesper\Main\EntityProto\Builder
 */
abstract class DTOBuilder extends PrototypedBuilder {

	protected function createEmpty() {
		$className = $this->proto->className() . 'DTO';

		return new $className;
	}
}
