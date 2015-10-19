<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Core\Form\Filter;

use Hesper\Core\Base\Singleton;

/**
 * Replaces \n and \r by whitespace
 * @package Hesper\Core\Form\Filter
 */
final class RemoveNewlineFilter extends BaseFilter {

	/**
	 * @return RemoveNewLineFilter
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function apply($value) {
		return preg_replace('/[\n\r]+/', ' ', $value);
	}
}
