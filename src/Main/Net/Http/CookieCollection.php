<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Evgeny V. Kokovikhin
 */
namespace Hesper\Main\Net\Http;

use Hesper\Main\Base\AbstractCollection;

/**
 * Class CookieCollection like java.utils.Collection Interface
 * see http://java.sun.com/javase/6/docs/api/java/util/Collection.html
 * @package Hesper\Main\Net\Http
 */
final class CookieCollection extends AbstractCollection {

	/**
	 * @return CookieCollection
	 **/
	public static function create() {
		return new self;
	}

	public function httpSetAll() {
		foreach ($this->items as $item) {
			$item->httpSet();
		}

		return $this;
	}
}
