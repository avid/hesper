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
 * Class JsonEncoderFilter
 * @package Hesper\Core\Form\Filter
 */
final class JsonEncoderFilter extends BaseFilter {

	/**
	 * @return JsonEncoderFilter
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function apply($value) {
		return json_encode($value);
	}
}
