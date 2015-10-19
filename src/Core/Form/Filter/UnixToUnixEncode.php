<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Filter;

use Hesper\Core\Base\Singleton;

/**
 * Uuencode a string.
 * @ingroup Filters
 **/
final class UnixToUnixEncode extends BaseFilter {

	/**
	 * @return UnixToUnixEncode
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function apply($value) {
		return convert_uuencode($value);
	}
}
