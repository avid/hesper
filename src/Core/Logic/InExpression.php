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
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Form\Form;
use Hesper\Core\OSQL\DialectString;
use Hesper\Core\OSQL\JoinCapableQuery;
use Hesper\Core\OSQL\Query;
use Hesper\Core\OSQL\SQLArray;
use Hesper\Main\Criteria\Criteria;
use Hesper\Main\DAO\ProtoDAO;

/**
 * Name says it all. :-)
 * @package Hesper\Core\Logic
 */
final class InExpression implements LogicalObject, MappableObject {

	const IN     = 'IN';
	const NOT_IN = 'NOT IN';

	private $left  = null;
	private $right = null;
	private $logic = null;

	public function __construct($left, $right, $logic) {
		Assert::isTrue(($right instanceof Query) || ($right instanceof Criteria) || ($right instanceof MappableObject) || is_array($right));

		Assert::isTrue(($logic == self::IN) || ($logic == self::NOT_IN));

		$this->left = $left;
		$this->right = $right;
		$this->logic = $logic;
	}

	/**
	 * @return InExpression
	 **/
	public function toMapped(ProtoDAO $dao, JoinCapableQuery $query) {
		if (is_array($this->right)) {
			$right = [];
			foreach ($this->right as $atom) {
				$right[] = $dao->guessAtom($atom, $query);
			}
		} elseif ($this->right instanceof MappableObject) {
			$right = $this->right->toMapped($dao, $query);
		} else {
			$right = $this->right;
		} // untransformable

		return new self($dao->guessAtom($this->left, $query), $right, $this->logic);
	}

	public function toDialectString(Dialect $dialect) {
		$string = '(' . $dialect->toFieldString($this->left) . ' ' . $this->logic . ' ';

		$right = $this->right;

		if ($right instanceof DialectString) {

			$string .= '(' . $right->toDialectString($dialect) . ')';

		} elseif (is_array($right)) {

			$string .= SQLArray::create($right)
			                   ->toDialectString($dialect);

		} else {
			throw new WrongArgumentException('sql select or array accepted by ' . $this->logic);
		}

		$string .= ')';

		return $string;
	}

	public function toBoolean(Form $form) {
		$left = $form->toFormValue($this->left);
		$right = $this->right;

		$both = (null !== $left) && (null !== $right);

		switch ($this->logic) {

			case self::IN:
				return $both && (in_array($left, $right));

			case self::NOT_IN:
				return $both && (!in_array($left, $right));

			default:

				throw new UnsupportedMethodException("'{$this->logic}' doesn't supported");
		}
	}
}
