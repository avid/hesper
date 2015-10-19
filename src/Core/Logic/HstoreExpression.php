<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Core\Logic;

use Hesper\Core\Base\StaticFactory;
use Hesper\Main\Base\Hstore;

/**
 * Class HstoreExpression
 * @see     http://www.postgresql.org/docs/8.3/interactive/hstore.html
 * @package Hesper\Core\Logic
 */
final class HstoreExpression extends StaticFactory {

	const CONTAIN      = '?';
	const GET_VALUE    = '->';
	const LEFT_CONTAIN = '@>';
	const CONCAT       = '||';

	/**
	 * @return BinaryExpression
	 **/
	public static function containKey($field, $key) {
		return new BinaryExpression($field, $key, self::CONTAIN);
	}

	/**
	 * @return BinaryExpression
	 **/
	public static function getValueByKey($field, $key) {
		return new BinaryExpression($field, $key, self::GET_VALUE);
	}

	/**
	 * @return BinaryExpression
	 **/
	public static function containValue($field, $key, $value) {
		return new BinaryExpression($field, "{$key}=>{$value}", self::LEFT_CONTAIN);
	}

	/**
	 * @return BinaryExpression
	 **/
	public static function concat($field, $value) {
		return new BinaryExpression($field, $value, self::CONCAT);
	}

	/**
	 * @return BinaryExpression
	 **/
	public static function containHstore($field, Hstore $hstore) {
		return new BinaryExpression($field, $hstore->toString(), self::LEFT_CONTAIN);
	}

	public static function containValueList($field, array $list) {
		return self::containHstore($field, Hstore::make($list));
	}
}
