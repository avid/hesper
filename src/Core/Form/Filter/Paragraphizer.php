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
 * Class Paragraphizer
 * @package Hesper\Core\Form\Filter
 */
final class Paragraphizer extends BaseFilter {

	/**
	 * @return Paragraphizer
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function apply($value) {
		return preg_replace('~^([^<].+)\s$~Uums', '<p>$1</p>' . "\n", $value);
	}
}
