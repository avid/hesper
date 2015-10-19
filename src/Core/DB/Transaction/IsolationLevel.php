<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\DB\Transaction;

use Hesper\Core\Base\Enumeration;

/**
 * Transaction isolation levels.
 * @see     http://www.postgresql.org/docs/current/interactive/sql-start-transaction.html
 * @package Hesper\Core\DB\Transaction
 */
final class IsolationLevel extends Enumeration {

	const READ_COMMITTED   = 0x01;
	const READ_UNCOMMITTED = 0x02;
	const REPEATABLE_READ  = 0x03;
	const SERIALIZABLE     = 0x04;

	protected $names = [self::READ_COMMITTED => 'read commited', self::READ_UNCOMMITTED => 'read uncommitted', self::REPEATABLE_READ => 'repeatable read', self::SERIALIZABLE => 'serializable'];
}
