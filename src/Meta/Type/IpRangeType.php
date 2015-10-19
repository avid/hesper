<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Evgeny V. Kokovikhin
 */
namespace Hesper\Meta\Type;

use Hesper\Main\Net\Ip\IpRange;

/**
 * Class IpRangeType
 * @package Hesper\Meta\Type
 */
class IpRangeType extends ObjectType {

	public function getPrimitiveName() {
		return 'ipRange';
	}

	public function getFullClass() {
		return IpRange::class;
	}

	public function isGeneric() {
		return true;
	}

	public function isMeasurable() {
		return true;
	}

	public function toColumnType() {
		return '\Hesper\Core\OSQL\DataType::iprange()';
	}
}
