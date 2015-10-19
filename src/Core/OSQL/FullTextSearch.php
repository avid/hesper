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
 * Class FullTextSearch
 * @package Hesper\Core\OSQL
 */
final class FullTextSearch extends FullText {

	public function toDialectString(Dialect $dialect) {
		return $dialect->fullTextSearch($this->field, $this->words, $this->logic);
	}
}
