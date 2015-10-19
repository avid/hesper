<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Core\Form\Filter;

use Hesper\Core\Base\Singleton;

/**
 * Class SafeUtf8Filter
 * @package Hesper\Core\Form\Filter
 */
final class SafeUtf8Filter extends BaseFilter {

	/**
	 * @return SafeUtf8Filter
	 **/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function apply($value) {
		$matches = null;

		// voodoo magic from w3 validator
		preg_match_all(
			'/[\x00-\x7F]                          ' # ASCII
			. '| [\xC2-\xDF]        [\x80-\xBF]    ' # non-overlong 2-byte sequences
			. '|  \xE0[\xA0-\xBF]   [\x80-\xBF]    ' # excluding overlongs
			. '| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} ' # straight 3-byte sequences
			. '|  \xED[\x80-\x9F]   [\x80-\xBF]    ' # excluding surrogates
			. '|  \xF0[\x90-\xBF]   [\x80-\xBF]{2} ' # planes 1-3
			. '| [\xF1-\xF3]        [\x80-\xBF]{3} ' # planes 4-15
			. '|  \xF4[\x80-\x8F][\x80-\xBF]{2}    ' # plane 16
			. '/x',
			$value, $matches);

		if (!isset($matches[0])) {
			return null;
		} else {
			return implode(null, $matches[0]);
		}
	}
}
