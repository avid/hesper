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
 * Class UnTypographizer
 * @package Hesper\Core\Form\Filter
 */
final class UnTypographizer extends BaseFilter {

	private static $symbols =
		array(
			'&nbsp;'	=> ' ',
			' &lt; '	=> ' < ',
			' &gt; '	=> ' > ',
			'&#133;'	=> '…',
			'&trade;'	=> '™',
			'&copy;'	=> '©',
			'&#8470;'	=> '№',
			'&#151;'	=> '—',
			'&mdash;'	=> '—',
			'&laquo;'	=> '«',
			'&raquo;'	=> '»',
			'&bull;'	=> '•',
			'&reg;'		=> '®',
			'&frac14;'	=> '¼',
			'&frac12;'	=> '½',
			'&frac34;'	=> '¾',
			'&plusmn;'	=> '±'
		);

	/**
	 * @return UnTypographizer
	**/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function apply($value) {
		return strtr($value, self::$symbols);
	}

}
