<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\DB;

use Hesper\Core\Base\Identifier;

/**
 * MySQL dialect with mysqli extension.
 * @package Hesper\Core\DB
 * @see     http://www.mysql.com/
 * @see     http://www.php.net/mysqli
 */
final class MyImprovedDialect extends MyDialect {

	public function quoteValue($value) {
		/// @see Sequenceless for this convention

		if ($value instanceof Identifier && !$value->isFinalized()) {
			return "''";
		} // instead of 'null', to be compatible with v. 4

		return "'" . mysqli_real_escape_string($this->getLink(), $value) . "'";
	}

	public function quoteBinary($data) {
		return "'" . mysqli_real_escape_string($this->getLink(), $data) . "'";
	}
}
