<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\Base\StaticFactory;

/**
 * Factory for OSQL's queries.
 * @see     http://onphp.org/examples.OSQL.en.html
 * @package Hesper\Core\OSQL
 */
final class OSQL extends StaticFactory {

	/**
	 * @return SelectQuery
	 **/
	public static function select() {
		return new SelectQuery();
	}

	/**
	 * @return InsertQuery
	 **/
	public static function insert() {
		return new InsertQuery();
	}

	/**
	 * @return UpdateQuery
	 **/
	public static function update($table = null) {
		return new UpdateQuery($table);
	}

	/**
	 * @return DeleteQuery
	 **/
	public static function delete() {
		return new DeleteQuery();
	}

	/**
	 * @return TruncateQuery
	 **/
	public static function truncate($whom = null) {
		return new TruncateQuery($whom);
	}

	/**
	 * @return CreateTableQuery
	 **/
	public static function createTable(DBTable $table) {
		return new CreateTableQuery($table);
	}

	/**
	 * @return DropTableQuery
	 **/
	public static function dropTable($name, $cascade = false) {
		return new DropTableQuery($name, $cascade);
	}
}
