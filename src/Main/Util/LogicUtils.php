<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Util;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\StaticFactory;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Logic\Expression;
use Hesper\Core\Logic\LogicalChain;
use Hesper\Core\OSQL\DBValue;

/**
 * Class LogicUtils
 * @package Hesper\Main\Util
 */
final class LogicUtils extends StaticFactory {

	/**
	 * @throws WrongArgumentException
	 * @return LogicalChain
	 **/
	public static function getOpenRange($left, $right, $min = null, $max = null) {
		Assert::isFalse(($min === null) && ($max === null), 'how can i build logic from emptyness?');

		if ($min !== null) {
			$min = new DBValue($min);
		}

		if ($max !== null) {
			$max = new DBValue($max);
		}

		$chain = new LogicalChain();

		if ($min !== null && $max !== null) {
			$chain->expOr(Expression::orBlock(Expression::andBlock(Expression::notNull($left), Expression::notNull($right), Expression::expOr(Expression::between($min, $left, $right), Expression::between($left, $min, $max))), Expression::andBlock(Expression::isNull($left), Expression::ltEq($min, $right)), Expression::andBlock(Expression::isNull($right), Expression::ltEq($left, $max)), Expression::andBlock(Expression::isNull($left), Expression::isNull($right))));
		} elseif ($min !== null && $max === null) {
			$chain->expOr(Expression::orBlock(Expression::andBlock(Expression::notNull($right), Expression::ltEq($min, $right)), Expression::isNull($right)));
		} elseif ($max !== null && $min === null) {
			$chain->expOr(Expression::orBlock(Expression::andBlock(Expression::notNull($left), Expression::ltEq($left, $max)), Expression::isNull($left)));
		}

		return $chain;
	}


	/**
	 * @throws WrongArgumentException
	 * @return LogicalChain
	 **/
	public static function getOpenPoint($left, $right, $point) {
		Assert::isFalse(($point === null), 'how can i build logic from emptyness?');

		$point = new DBValue($point);

		$chain = new LogicalChain();

		$chain->expOr(Expression::orBlock(Expression::andBlock(Expression::notNull($left), Expression::notNull($right), Expression::between($point, $left, $right)), Expression::andBlock(Expression::isNull($left), Expression::ltEq($point, $right)), Expression::andBlock(Expression::isNull($right), Expression::ltEq($left, $point)), Expression::andBlock(Expression::isNull($left), Expression::isNull($right))));

		return $chain;
	}
}
