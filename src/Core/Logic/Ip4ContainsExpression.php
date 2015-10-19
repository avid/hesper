<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Evgeny V. Kokovikhin
 */
namespace Hesper\Core\Logic;

use Hesper\Core\DB\Dialect;
use Hesper\Core\Exception\UnimplementedFeatureException;
use Hesper\Core\Form\Form;
use Hesper\Core\OSQL\JoinCapableQuery;
use Hesper\Main\DAO\ProtoDAO;

/**
 * Class Ip4ContainsExpression
 * @package Hesper\Core\Logic
 */
final class Ip4ContainsExpression implements LogicalObject, MappableObject {

	private $range = null;
	private $ip    = null;

	public function __construct($range, $ip) {
		$this->range = $range;
		$this->ip = $ip;
	}

	public function toDialectString(Dialect $dialect) {
		return $dialect->quoteIpInRange($this->range, $this->ip);
	}

	public function toMapped(ProtoDAO $dao, JoinCapableQuery $query) {
		return new self($dao->guessAtom($this->range, $query), $dao->guessAtom($this->ip, $query));
	}

	public function toBoolean(Form $form) {
		throw new UnimplementedFeatureException('Author was too lazy to make it');
	}
}
