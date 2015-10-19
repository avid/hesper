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
 * Class JsonDecoderFilter
 * @package Hesper\Core\Form\Filter
 */
final class JsonDecoderFilter extends BaseFilter {

	private $assoc = false;

	/**
	 * @return JsonDecoderFilter
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	/**
	 * @return JsonDecoderFilter
	 **/
	public function setAssoc($orly = true) {
		$this->assoc = (true === $orly);

		return $this;
	}

	public function apply($value) {
		return json_decode($value, $this->assoc);
	}
}
