<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Sergey S. Sergeev
 */
namespace Hesper\Core\Logic;

use Hesper\Core\Base\StaticFactory;

/**
 * Extensive facilities for searching through label trees are provided.
 * @see     http://www.postgresql.org/docs/current/interactive/ltree.html
 * @ingroup Logic
 **/
final class LTreeExpression extends StaticFactory {

	const ANCESTOR   = '@>';
	const DESCENDANT = '<@';
	const MATCH      = '~';
	const SEARCH     = '@';

	/**
	 * Is left argument an ancestor of right (or equal)?
	 * @return BinaryExpression
	 **/
	public static function ancestor($left, $right) {
		return new BinaryExpression($left, $right, self::ANCESTOR);
	}

	/**
	 * Is left argument a descendant of right (or equal)?
	 * @return BinaryExpression
	 **/
	public static function descendant($left, $right) {
		return new BinaryExpression($left, $right, self::DESCENDANT);
	}

	/**
	 * @return BinaryExpression
	 **/
	public static function match($ltree, $lquery) {
		return new BinaryExpression($ltree, $lquery, self::MATCH);
	}

	/**
	 * @return BinaryExpression
	 **/
	public static function search($ltree, $ltxtquery) {
		return new BinaryExpression($ltree, $ltxtquery, self::SEARCH);
	}
}