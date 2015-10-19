<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\OSQL;

use Hesper\Core\Base\Assert;
use Hesper\Core\DB\Dialect;
use Hesper\Core\Logic\MappableObject;
use Hesper\Main\DAO\ProtoDAO;

/**
 * Class ExtractPart
 * @package Hesper\Core\OSQL
 */
final class ExtractPart implements DialectString, MappableObject {

	private $what = null;
	private $from = null;

	public static function create(/* DatePart */
		$what, /* DialectString */
		$from) {
		return new self($what, $from);
	}

	public function __construct(/* DatePart */
		$what, /* DialectString */
		$from) {
		if ($from instanceof DialectString) {
			Assert::isTrue(($from instanceof DBValue) || ($from instanceof DBField));
		} else {
			$from = new DBField($from);
		}

		if (!$what instanceof DatePart) {
			$what = new DatePart($what);
		}

		$this->what = $what;
		$this->from = $from;
	}

	/**
	 * @return ExtractPart
	 **/
	public function toMapped(ProtoDAO $dao, JoinCapableQuery $query) {
		return self::create($this->what, $dao->guessAtom($this->from, $query));
	}

	public function toDialectString(Dialect $dialect) {
		return 'EXTRACT(' . $this->what->toString() . ' FROM ' . $this->from->toDialectString($dialect) . ')';
	}
}
