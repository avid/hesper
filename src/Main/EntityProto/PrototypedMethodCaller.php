<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\EntityProto;

/**
 * Class PrototypedMethodCaller
 * @package Hesper\Main\EntityProto
 */
abstract class PrototypedMethodCaller {

	protected $proto  = null;
	protected $object = null;

	protected $mapping = [];

	public function __construct(EntityProto $proto, &$object) {
		$this->proto = $proto;
		$this->object = &$object;

		$this->mapping = $proto->getFormMapping();
	}
}
