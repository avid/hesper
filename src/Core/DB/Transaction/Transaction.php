<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\DB\Transaction;

use Hesper\Core\Base\StaticFactory;
use Hesper\Core\DB\DB;

/**
 * Transaction's factory.
 * @package Hesper\Core\DB\Transaction
 */
final class Transaction extends StaticFactory {

	/**
	 * @return DBTransaction
	 **/
	public static function immediate(DB $db) {
		return new DBTransaction($db);
	}

	/**
	 * @return TransactionQueue
	 **/
	public static function deferred(DB $db) {
		return new TransactionQueue($db);
	}

	/**
	 * @return FakeTransaction
	 **/
	public static function fake(DB $db) {
		return new FakeTransaction($db);
	}
}
