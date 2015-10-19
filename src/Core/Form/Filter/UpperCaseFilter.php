<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey M. Skachkov
 */
namespace Hesper\Core\Form\Filter;

use Hesper\Core\Base\Singleton;

/**
 * Class UpperCaseFilter
 * @package Hesper\Core\Form\Filter
 */
final class UpperCaseFilter extends BaseFilter {

	/**
	 * @return LowerCaseFilter
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function apply($value) {
		return mb_strtoupper($value);
	}
}
