<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\Base\Aliased;
use Hesper\Core\DB\Dialect;
use Hesper\Core\Logic\LogicalObject;

/**
 * Class SQLBaseJoin
 * @package Hesper\Core\OSQL
 */
abstract class SQLBaseJoin implements SQLTableName, SQLRealTableName, Aliased {

	protected $subject = null;
	protected $alias   = null;
	protected $logic   = null;

	public function __construct($subject, LogicalObject $logic, $alias) {
		$this->subject = $subject;
		$this->alias = $alias;
		$this->logic = $logic;
	}

	public function getAlias() {
		return $this->alias;
	}

	public function getTable() {
		return $this->alias ? $this->alias : $this->subject;
	}

	public function getRealTable() {
		return $this->subject;
	}

	protected function baseToString(Dialect $dialect, $logic = null) {
		return
			$logic.'JOIN '
				.($this->subject instanceof DialectString
					?
						$this->subject instanceof Query
							? '('.$this->subject->toDialectString($dialect).')'
							: $this->subject->toDialectString($dialect)
					: $dialect->quoteTable($this->subject)
				)
			.($this->alias ? ' AS '.$dialect->quoteTable($this->alias) : null)
			.' ON '.$this->logic->toDialectString($dialect);
	}
}
