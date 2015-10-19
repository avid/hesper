<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Primitive;

use Hesper\Core\Base\Timestamp;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Main\Base\TimestampRange;

/**
 * Class PrimitiveTimestampRange
 * @package Hesper\Core\Form\Primitive
 */
final class PrimitiveTimestampRange extends PrimitiveDateRange {

	private $className = null;

	/**
	 * @return PrimitiveTimestampRange
	 **/
	public static function create($name) {
		return new self($name);
	}

	protected function getObjectName() {
		return '\Hesper\Core\Base\TimestampRange';
	}

	protected function makeRange($string) {
		if (strpos($string, ' - ') !== false) {
			list($first, $second) = explode(' - ', $string);

			return TimestampRange::create(new Timestamp(trim($first)), new Timestamp(trim($second)));
		}

		throw new WrongArgumentException();
	}
}
