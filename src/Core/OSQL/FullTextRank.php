<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich, Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\DB\Dialect;

/**
 * Full-text ranking. Mostly used in "ORDER BY".
 * @package Hesper\Core\OSQL
 */
final class FullTextRank extends FullText {

	public function toDialectString(Dialect $dialect) {
		return $dialect->fullTextRank($this->field, $this->words, $this->logic);
	}
}
