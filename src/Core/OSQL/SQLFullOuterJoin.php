<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Timofey A. Anisimov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\DB\Dialect;

/**
 * Class SQLFullOuterJoin
 * @package Hesper\Core\OSQL
 */
final class SQLFullOuterJoin extends SQLBaseJoin {

	/**
	 * @param Dialect $dialect
	 *
	 * @return string
	 */
	public function toDialectString(Dialect $dialect) {
		return parent::baseToString($dialect, 'FULL OUTER ');
	}

}
