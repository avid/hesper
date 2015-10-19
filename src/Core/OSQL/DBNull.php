<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\DB\Dialect;
use Hesper\Core\Exception\UnsupportedMethodException;
use Hesper\Core\Form\Form;
use Hesper\Core\Logic\LogicalObject;

/**
 * DB null value
 * @package Hesper\Core\OSQL
 */
final class DBNull implements LogicalObject {

	public function toDialectString(Dialect $dialect) {
		return 'NULL';
	}

	public function toBoolean(Form $form) {
		throw new UnsupportedMethodException();
	}
}
