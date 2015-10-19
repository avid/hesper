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
 * Decode a uuencoded string.
 * @ingroup Filters
 **/
final class UnixToUnixDecode extends BaseFilter {

	/**
	 * @return UnixToUnixDecode
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function apply($value) {
		return convert_uudecode($value);
	}
}
