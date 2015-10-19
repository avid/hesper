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
 * Class UrlDecodeFilter
 * @package Hesper\Core\Form\Filter
 */
final class UrlDecodeFilter extends BaseFilter {

	/**
	 * @return UrlDecodeFilter
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function apply($value) {
		return urldecode($value);
	}
}
