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
 * Class PrefixUnaryExpression
 * @package Hesper\Core\Logic
 */
final class PrefixUnaryExpression implements LogicalObject, MappableObject {

	const NOT   = 'NOT';
	const MINUS = '-';

	private $subject  = null;
	private $logic    = null;
	private $brackets = true;

	/**
	 * @return PrefixUnaryExpression
	 */
	public static function create($subject, $logic) {
		return new self($subject, $logic);
	}

	public function __construct($logic, $subject) {
		$this->subject = $subject;
		$this->logic = $logic;
	}

	/**
	 * @param boolean $noBrackets
	 *
	 * @return PrefixUnaryExpression
	 */
	public function noBrackets($noBrackets = true) {
		$this->brackets = !$noBrackets;

		return $this;
	}

	public function toDialectString(Dialect $dialect) {
		$sql = $dialect->logicToString($this->logic) . ' ' . $dialect->toFieldString($this->subject);

		return $this->brackets ? "({$sql})" : $sql;
	}

	/**
	 * @return PrefixUnaryExpression
	 **/
	public function toMapped(ProtoDAO $dao, JoinCapableQuery $query) {
		$expression = new self($this->logic, $dao->guessAtom($this->subject, $query));

		return $expression->noBrackets($this->brackets);
	}

	public function toBoolean(Form $form) {
		Assert::isTrue($this->brackets, 'brackets must be enabled');
		$subject = $form->toFormValue($this->subject);

		switch ($this->logic) {
			case self::NOT :
				return false === $subject;

			default:

				throw new UnsupportedMethodException("'{$this->logic}' doesn't supported yet");
		}
	}
}
