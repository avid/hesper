<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Core\OSQL;

/**
 * Interface SQLTableName
 */
interface SQLTableName extends DialectString {

	public function getTable();
}
