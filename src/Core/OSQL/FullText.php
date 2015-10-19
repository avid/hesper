<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich, Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\UnsupportedMethodException;
use Hesper\Core\Form\Form;
use Hesper\Core\Logic\LogicalObject;
use Hesper\Core\Logic\MappableObject;
use Hesper\Main\DAO\ProtoDAO;

/**
 * Base for all full-text stuff.
 * @package Hesper\Core\OSQL
 */
abstract class FullText implements DialectString, MappableObject, LogicalObject {

	protected $logic = null;
	protected $field = null;
	protected $words = null;

	public function __construct($field, $words, $logic) {
		if (is_string($field)) {
			$field = new DBField($field);
		}

		Assert::isArray($words);

		$this->field = $field;
		$this->words = $words;
		$this->logic = $logic;
	}

	/**
	 * @return FullText
	 **/
	public function toMapped(ProtoDAO $dao, JoinCapableQuery $query) {
		return new $this($dao->guessAtom($this->field, $query, $dao->getTable()), $this->words, $this->logic);
	}

	public function toBoolean(Form $form) {
		throw new UnsupportedMethodException();
	}
}
