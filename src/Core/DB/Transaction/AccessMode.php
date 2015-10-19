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
 * Transaction access modes.
 * @package Hesper\Core\DB\Transaction
 * @see     http://www.postgresql.org/docs/current/interactive/sql-start-transaction.html
 */
final class AccessMode extends Enumeration {

	const READ_ONLY  = 0x01;
	const READ_WRITE = 0x02;

	protected $names = [self::READ_ONLY => 'read only', self::READ_WRITE => 'read write'];
}
