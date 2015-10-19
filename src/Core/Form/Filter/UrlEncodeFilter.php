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
 * Class UrlEncodeFilter
 * @package Hesper\Core\Form\Filter
 */
final class UrlEncodeFilter extends BaseFilter {

	/**
	 * @return UrlEncodeFilter
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function apply($value) {
		return urlencode($value);
	}
}
