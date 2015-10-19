<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov, Anton E. Lebedevich
 */
namespace Hesper\Core\Logic;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Form\Form;
use Hesper\Core\OSQL\SelectQuery;
use Hesper\Core\OSQL\SQLChain;

/**
 * Wrapper around given childs of LogicalObject with custom logic-glue's.
 * @package Hesper\Core\Logic
 */
final class LogicalChain extends SQLChain {

	/**
	 * @return LogicalChain
	 **/
	public static function block($args, $logic) {
		Assert::isTrue(($logic == BinaryExpression::EXPRESSION_AND) || ($logic == BinaryExpression::EXPRESSION_OR),

			"unknown logic '{$logic}'");

		$logicalChain = new self;

		foreach ($args as $arg) {
			if (!$arg instanceof LogicalObject && !$arg instanceof SelectQuery) {
				throw new WrongArgumentException('unsupported object type: ' . get_class($arg));
			}

			$logicalChain->exp($arg, $logic);
		}

		return $logicalChain;
	}

	/**
	 * @return LogicalChain
	 **/
	public function expAnd(LogicalObject $exp) {
		return $this->exp($exp, BinaryExpression::EXPRESSION_AND);
	}

	/**
	 * @return LogicalChain
	 **/
	public function expOr(LogicalObject $exp) {
		return $this->exp($exp, BinaryExpression::EXPRESSION_OR);
	}

	public function toBoolean(Form $form) {
		$chain = &$this->chain;

		$size = count($chain);

		if (!$size) {
			throw new WrongArgumentException('empty chain can not be calculated');
		} elseif ($size == 1) {
			return $chain[0]->toBoolean($form);
		} else { // size > 1
			$out = $chain[0]->toBoolean($form);

			for ($i = 1; $i < $size; ++$i) {
				$out = self::calculateBoolean($this->logic[$i], $out, $chain[$i]->toBoolean($form));
			}

			return $out;
		}
	}

	private static function calculateBoolean($logic, $left, $right) {
		switch ($logic) {
			case BinaryExpression::EXPRESSION_AND:
				return $left && $right;

			case BinaryExpression::EXPRESSION_OR:
				return $left || $right;

			default:
				throw new WrongArgumentException("unknown logic - '{$logic}'");
		}
	}
}
