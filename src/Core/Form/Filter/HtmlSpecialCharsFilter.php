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
 * HTML Special Characters replacer.
 * @package Hesper\Core\Form\Filter
 */
final class HtmlSpecialCharsFilter extends BaseFilter {

	/**
	 * @return HtmlSpecialCharsFilter
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function apply($value) {
		return htmlspecialchars($value);
	}

}
