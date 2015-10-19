<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Core\Form\Filter;

use Hesper\Core\Base\Singleton;

/**
 * Unserialize string
 * @deprecated Because of the potential security problem.
 * @package Hesper\Core\Form\Filter
 */
final class UnserializeFilter extends BaseFilter {

	/**
	 * @return UnserializeFilter
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function apply($value) {
		return unserialize($value);
	}
}
