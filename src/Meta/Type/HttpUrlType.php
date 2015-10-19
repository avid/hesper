<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Meta\Type;

use Hesper\Main\Net\HttpUrl;

/**
 * Class HttpUrlType
 * @package Hesper\Meta\Type
 */
final class HttpUrlType extends ObjectType {

	public function getPrimitiveName() {
		return 'httpUrl';
	}

	public function getFullClass() {
		return HttpUrl::class;
	}

	public function isGeneric() {
		return true;
	}

	public function isMeasurable() {
		return true;
	}

	public function toColumnType() {
		return '\Hesper\Core\OSQL\DataType::varchar()';
	}
}
