<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Core\Form\Filter;

use Hesper\Main\Net\HttpUrl;

/**
 * Class NormalizeUrlFilter
 * @see     RegulatedPrimitive::addImportFilter()
 * @package Hesper\Core\Form\Filter
 */
final class NormalizeUrlFilter implements Filtrator {

	/**
	 * @return NormalizeUrlFilter
	 **/
	public static function create() {
		return new self;
	}


	public function apply($value) {
		$url = HttpUrl::create()->parse($value)->ensureAbsolute()->normalize();

		return $url->toString();
	}
}
