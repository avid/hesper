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

/**
 * Class DropTableQuery
 * @package Hesper\Core\OSQL
 */
final class DropTableQuery extends QueryIdentification {

	private $name = null;

	private $cascade = false;

	public function getId() {
		throw new UnsupportedMethodException();
	}

	public function __construct($name, $cascade = false) {
		$this->name = $name;
		$this->cascade = (true === $cascade);
	}

	public function toDialectString(Dialect $dialect) {
		return 'DROP TABLE ' . $dialect->quoteTable($this->name) . $dialect->dropTableMode($this->cascade) . ';';
	}
}
