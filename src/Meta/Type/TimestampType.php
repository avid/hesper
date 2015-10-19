<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Type;

use Hesper\Core\Base\Timestamp;

final class TimestampType extends DateType {

	public function getPrimitiveName() {
		return 'timestamp';
	}

	public function getFullClass() {
		return Timestamp::class;
	}

	public function toColumnType() {
		return '\Hesper\Core\OSQL\DataType::timestamp()';
	}

}
