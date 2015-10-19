<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Entity;

use Hesper\Core\Base\Enumeration;
use Hesper\Core\Exception\WrongArgumentException;

/**
 * Class MetaRelation
 * @package Hesper\Meta\Entity
 */
final class MetaRelation extends Enumeration {

	const ONE_TO_ONE   = 1;
	const ONE_TO_MANY  = 2;
	const MANY_TO_MANY = 3;

	protected $names = [self::ONE_TO_ONE => 'OneToOne', self::ONE_TO_MANY => 'OneToMany', self::MANY_TO_MANY => 'ManyToMany'];

	/**
	 * @return MetaRelation
	 **/
	public static function create($id) {
		return new self($id);
	}

	/**
	 * @return MetaRelation
	 **/
	public static function makeFromName($name) {
		$self = self::create(self::getAnyId());
		$id = array_search($name, $self->getNameList());

		if ($id) {
			return $self->setId($id);
		}

		throw new WrongArgumentException();
	}
}

?>