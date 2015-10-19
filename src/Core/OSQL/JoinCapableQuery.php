<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\Logic\LogicalObject;

/**
 * Interface JoinCapableQuery
 * @package Hesper\Core\OSQL
 */
interface JoinCapableQuery {

	public function from($table, $alias = null);

	public function join($table, LogicalObject $logic, $alias = null);

	public function leftJoin($table, LogicalObject $logic, $alias = null);

	public function rightJoin($table, LogicalObject $logic, $alias = null);

	public function hasJoinedTable($table);
}
