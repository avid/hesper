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
 * Replaces multiple adjacent whitespace by one
 * @see     RegulatedPrimitive::addImportFilter()
 * @package Hesper\Core\Form\Filter
 */
final class CompressWhitespaceFilter extends BaseFilter {

	/**
	 * @return CompressWhitespaceFilter
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function apply($value) {
		return preg_replace('/[ \t]+/', ' ', $value);
	}
}
