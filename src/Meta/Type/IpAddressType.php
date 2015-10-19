<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Evgeny V. Kokovikhin
 */
namespace Hesper\Meta\Type;

use Hesper\Main\Net\Ip\IpAddress;

/**
 * Class IpAddressType
 * @package Hesper\Meta\Type
 */
class IpAddressType extends ObjectType {

	public function getPrimitiveName() {
		return 'ipAddress';
	}

	public function getFullClass() {
		return IpAddress::class;
	}

	public function isGeneric() {
		return true;
	}

	public function isMeasurable() {
		return true;
	}

	public function toColumnType() {
		return '\Hesper\Core\OSQL\DataType::ip()';
	}
}
