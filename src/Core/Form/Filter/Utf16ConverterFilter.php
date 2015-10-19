<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Core\Form\Filter;

use Hesper\Core\Base\Singleton;

/**
 * Used for on-fly detection and turning UTF16 into UTF8.
 * Normally, you should not use this class. There are a little amount of
 * platforms with broken unicode implementations, and this filter tries to
 * detect them and fix their bug.
 * Not working for UTF-16LE, though.
 * @package Hesper\Core\Form\Filter
 */
final class Utf16ConverterFilter extends BaseFilter {

	/**
	 * @return Utf16ConverterFilter
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function apply($value) {
		if (mb_check_encoding($value, 'UTF-16') && mb_substr_count($value, "\000") > 0) {
			$value = mb_convert_encoding($value, 'UTF-8', 'UTF-16');
		}

		return $value;
	}
}
