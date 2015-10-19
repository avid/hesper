<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Logic;

use Hesper\Core\OSQL\DialectString;
use Hesper\Core\OSQL\JoinCapableQuery;
use Hesper\Main\DAO\ProtoDAO;

/**
 * Interface MappableObject
 * @package Hesper\Core\Logic
 */
interface MappableObject extends DialectString {

	/**
	 * @return MappableObject
	 **/
	public function toMapped(ProtoDAO $dao, JoinCapableQuery $query);
}
