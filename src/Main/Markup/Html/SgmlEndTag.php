<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Markup\Html;

/**
 * Class SgmlEndTag
 * @package Hesper\Main\Markup\Html
 */
final class SgmlEndTag extends SgmlTag {

	/**
	 * @return SgmlEndTag
	 **/
	public static function create() {
		return new self;
	}
}
