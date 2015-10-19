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
 * Class SQLLeftJoin
 * @package Hesper\Core\OSQL
 */
final class SQLLeftJoin extends SQLBaseJoin {

	public function toDialectString(Dialect $dialect) {
		return parent::baseToString($dialect, 'LEFT ');
	}
}
