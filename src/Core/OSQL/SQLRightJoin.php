<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\DB\Dialect;

/**
 * @ingroup OSQL
**/
final class SQLRightJoin extends SQLBaseJoin
{
	public function toDialectString(Dialect $dialect)
	{
		return parent::baseToString($dialect, 'RIGHT ');
	}
}
