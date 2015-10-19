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
use Hesper\Core\OSQL\SQLFunction;
use Hesper\Main\DAO\ProtoDAO;

/**
 * @ingroup Logic
**/
final class EqualsLowerExpression implements LogicalObject, MappableObject {

	private $left	= null;
	private $right	= null;

	public function __construct($left, $right)
	{
		$this->left		= $left;
		$this->right	= $right;
	}

	public function toDialectString(Dialect $dialect)
	{
		return
			'('
			.$dialect->toFieldString(
				SQLFunction::create('lower', $this->left)
			).' = '
			.$dialect->toValueString(
				is_string($this->right)
					? mb_strtolower($this->right)
					: SQLFunction::create('lower', $this->right)
			)
			.')';
	}

	/**
	 * @return EqualsLowerExpression
	**/
	public function toMapped(ProtoDAO $dao, JoinCapableQuery $query)
	{
		return new self(
			$dao->guessAtom($this->left, $query),
			$dao->guessAtom($this->right, $query)
		);
	}

	public function toBoolean(Form $form)
	{
		$left	= $form->toFormValue($this->left);
		$right	= $form->toFormValue($this->right);

		$both =
			(null !== $left)
			&& (null !== $right);

		return $both && (mb_strtolower($left) === mb_strtolower($right));
	}
}
