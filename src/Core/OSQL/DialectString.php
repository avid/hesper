<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\DB\Dialect;

/**
 * Basis for almost all implementations of SQL parts.
 * @package Hesper\Core\OSQL
 */
interface DialectString {

	public function toDialectString(Dialect $dialect);

}
