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
 * Inserts HTML line breaks before all newlines in a string.
 * @package Hesper\Core\Form\Filter
 */
final class NewLinesToBreaks extends BaseFilter {

	/**
	 * @return NewLinesToBreaks
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function apply($value) {
		return nl2br($value);
	}
}
