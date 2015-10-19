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
 * @deprecated since the begining of time
 * @package Hesper\Core\OSQL
 */
final class DBRaw implements LogicalObject {

	private $string = null;

	public function __construct($rawString) {
		if (!defined('__I_HATE_MY_KARMA__')) {
			throw new UnsupportedMethodException('do not use it. please.');
		}

		$this->string = $rawString;
	}

	public function toDialectString(Dialect $dialect) {
		return $this->string;
	}

	public function toBoolean(Form $form) {
		throw new UnsupportedMethodException();
	}
}
