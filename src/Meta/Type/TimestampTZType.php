<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Type;

use Hesper\Core\Base\TimestampTZ;

final class TimestampTZType extends DateType {

	public function getPrimitiveName() {
		return 'timestampTZ';
	}

	public function getFullClass() {
		return TimestampTZ::class;
	}

	public function toColumnType() {
		return '\Hesper\Core\OSQL\DataType::timestamptz()->setTimezoned(true)';
	}
}
