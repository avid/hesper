<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form;

use Hesper\Core\Base\StaticFactory;
use Hesper\Core\Form\Filter\CropFilter;
use Hesper\Core\Form\Filter\FilterChain;
use Hesper\Core\Form\Filter\HashFilter;
use Hesper\Core\Form\Filter\HtmlSpecialCharsFilter;
use Hesper\Core\Form\Filter\LowerCaseFilter;
use Hesper\Core\Form\Filter\NewLinesToBreaks;
use Hesper\Core\Form\Filter\PasswordHash;
use Hesper\Core\Form\Filter\PCREFilter;
use Hesper\Core\Form\Filter\SafeUtf8Filter;
use Hesper\Core\Form\Filter\StringReplaceFilter;
use Hesper\Core\Form\Filter\StripTagsFilter;
use Hesper\Core\Form\Filter\TrimFilter;
use Hesper\Core\Form\Filter\UnixToUnixDecode;
use Hesper\Core\Form\Filter\UnixToUnixEncode;
use Hesper\Core\Form\Filter\UpperCaseFilter;
use Hesper\Core\Form\Filter\UrlDecodeFilter;
use Hesper\Core\Form\Filter\UrlEncodeFilter;

/**
 * Factory for Filtrator implementations.
 * @package Hesper\Core\Form
 */
final class Filter extends StaticFactory {

	/**
	 * @return FilterChain
	 **/
	public static function textImport() {
		return FilterChain::create()->add(Filter::stripTags())->add(Filter::trim());
	}

	/**
	 * @return FilterChain
	 **/
	public static function chain() {
		return new FilterChain();
	}

	/**
	 * @return HashFilter
	 **/
	public static function hash($binary = false) {
		return HashFilter::create($binary);
	}

	/**
	 * @return PasswordHash
	 **/
	public static function passwordHash($algorithm = PASSWORD_BCRYPT) {
		return PasswordHash::create($algorithm);
	}

	/**
	 * @return PCREFilter
	 **/
	public static function pcre() {
		return PCREFilter::create();
	}

	/**
	 * @return TrimFilter
	 **/
	public static function trim() {
		return TrimFilter::create();
	}

	/**
	 * @return CropFilter
	 **/
	public static function crop() {
		return CropFilter::create();
	}

	/**
	 * @return StripTagsFilter
	 **/
	public static function stripTags() {
		return StripTagsFilter::create();
	}

	/**
	 * @return LowerCaseFilter
	 **/
	public static function lowerCase() {
		return LowerCaseFilter::me();
	}

	/**
	 * @return UpperCaseFilter
	 **/
	public static function upperCase() {
		return UpperCaseFilter::me();
	}

	/**
	 * @return HtmlSpecialCharsFilter
	 **/
	public static function htmlSpecialChars() {
		return HtmlSpecialCharsFilter::me();
	}

	/**
	 * @return NewLinesToBreaks
	 **/
	public static function nl2br() {
		return NewLinesToBreaks::me();
	}

	/**
	 * @return UrlEncodeFilter
	 **/
	public static function urlencode() {
		return UrlEncodeFilter::me();
	}

	/**
	 * @return UrlDecodeFilter
	 **/
	public static function urldecode() {
		return UrlDecodeFilter::me();
	}

	/**
	 * @return UnixToUnixDecode
	 **/
	public static function uudecode() {
		return UnixToUnixDecode::me();
	}

	/**
	 * @return UnixToUnixEncode
	 **/
	public static function uuencode() {
		return UnixToUnixEncode::me();
	}

	/**
	 * @return StringReplaceFilter
	 **/
	public static function replaceSymbols($search = null, $replace = null) {
		return StringReplaceFilter::create($search, $replace);
	}

	/**
	 * @return SafeUtf8Filter
	 **/
	public static function safeUtf8() {
		return SafeUtf8Filter::me();
	}
}
