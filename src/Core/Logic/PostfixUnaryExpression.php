<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov, Anton E. Lebedevich
 */
namespace Hesper\Core\Logic;

use Hesper\Core\Base\Assert;
use Hesper\Core\DB\Dialect;
use Hesper\Core\Exception\UnsupportedMethodException;
use Hesper\Core\Form\Form;
use Hesper\Core\OSQL\JoinCapableQuery;
use Hesper\Main\DAO\ProtoDAO;

/**
 * Class PostfixUnaryExpression
 * @package Hesper\Core\Logic
 */
final class PostfixUnaryExpression implements LogicalObject, MappableObject {

	const IS_NULL     = 'IS NULL';
	const IS_NOT_NULL = 'IS NOT NULL';

	const IS_TRUE  = 'IS TRUE';
	const IS_FALSE = 'IS FALSE';

	private $subject  = null;
	private $logic    = null;
	private $brackets = true;

	/**
	 * @return PostfixUnaryExpression
	 */
	public static function create($subject, $logic) {
		return new self($subject, $logic);
	}

	public function __construct($subject, $logic) {
		$this->subject = $subject;
		$this->logic = $logic;
	}

	/**
	 * @param boolean $noBrackets
	 *
	 * @return PostfixUnaryExpression
	 */
	public function noBrackets($noBrackets = true) {
		$this->brackets = !$noBrackets;

		return $this;
	}

	public function toDialectString(Dialect $dialect) {
		$sql = $dialect->toFieldString($this->subject) . ' ' . $dialect->logicToString($this->logic);

		return $this->brackets ? "({$sql})" : $sql;
	}

	/**
	 * @return PostfixUnaryExpression
	 **/
	public function toMapped(ProtoDAO $dao, JoinCapableQuery $query) {
		$expression = new self($dao->guessAtom($this->subject, $query), $this->logic);

		return $expression->noBrackets(!$this->brackets);
	}

	public function toBoolean(Form $form) {
		Assert::isTrue($this->brackets, 'brackets must be enabled');
		$subject = $form->toFormValue($this->subject);

		switch ($this->logic) {
			case self::IS_NULL:
				return null === $subject;

			case self::IS_NOT_NULL:
				return null !== $subject;

			case self::IS_TRUE:
				return true === $subject;

			case self::IS_FALSE:
				return false === $subject;

			default:

				throw new UnsupportedMethodException("'{$this->logic}' doesn't supported yet");
		}
	}
}
