<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Vladimir A. Altuchov
 */
namespace Hesper\Core\Form\Filter;

use Hesper\Core\Base\Singleton;

/**
 * Class LowerCaseFilter
 * @package Hesper\Core\Form\Filter
 */
final class LowerCaseFilter extends BaseFilter {

	/**
	 * @return LowerCaseFilter
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function apply($value) {
		return mb_strtolower($value);
	}
}
