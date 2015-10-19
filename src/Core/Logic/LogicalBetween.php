<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov, Anton E. Lebedevich
 */
namespace Hesper\Core\Logic;

use Hesper\Core\DB\Dialect;
use Hesper\Core\Form\Form;
use Hesper\Core\OSQL\JoinCapableQuery;
use Hesper\Main\DAO\ProtoDAO;

/**
 * SQL's BETWEEN or logical check whether value in-between given limits.
 * @package Hesper\Core\Logic
 */
final class LogicalBetween implements LogicalObject, MappableObject {

	private $field  = null;
	private $left   = null;
	private $right  = null;

	public function __construct($field, $left, $right)
	{
		$this->left		= $left;
		$this->right	= $right;
		$this->field	= $field;
	}

	public function toDialectString(Dialect $dialect)
	{
		return
			'('
			.$dialect->toFieldString($this->field)
			.' BETWEEN '
			.$dialect->toValueString($this->left)
			.' AND '
			.$dialect->toValueString($this->right)
			.')';
	}

	/**
	 * @return LogicalBetween
	**/
	public function toMapped(ProtoDAO $dao, JoinCapableQuery $query)
	{
		return new self(
			$dao->guessAtom($this->field, $query),
			$dao->guessAtom($this->left, $query),
			$dao->guessAtom($this->right, $query)
		);
	}

	public function toBoolean(Form $form)
	{
		$left	= $form->toFormValue($this->left);
		$right	= $form->toFormValue($this->right);
		$value	= $form->toFormValue($this->field);

		return ($left	<= $value)
			&& ($value	<= $right);
	}
}