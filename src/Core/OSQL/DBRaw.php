<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\DB\Dialect;
use Hesper\Core\Exception\UnsupportedMethodException;
use Hesper\Core\Form\Form;
use Hesper\Core\Logic\LogicalObject;

/**
 * Karma's destroyer.
 * Do not use it. Please.
 * @package Hesper\Core\OSQL
 */
final class DBRaw implements LogicalObject {

	private $string = null;

	public static function create($rawString) {
		return new self($rawString);
	}

	public function __construct($rawString) {
		$this->string = $rawString;
	}

	public function toDialectString(Dialect $dialect) {
		return $this->string;
	}

	public function toBoolean(Form $form) {
		throw new UnsupportedMethodException();
	}
}
