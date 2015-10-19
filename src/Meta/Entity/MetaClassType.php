<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Entity;

use Hesper\Core\Base\Enumeration;

/**
 * Class MetaClassType
 * @package Hesper\Meta\Entity
 */
final class MetaClassType extends Enumeration {

	const CLASS_FINAL    = 'final';
	const CLASS_ABSTRACT = 'abstract';
	const CLASS_SPOOKED  = 'spooked';

	protected $names = [self::CLASS_FINAL => self::CLASS_FINAL, self::CLASS_ABSTRACT => self::CLASS_ABSTRACT, self::CLASS_SPOOKED => self::CLASS_SPOOKED];

	public static function getAnyId() {
		return self::CLASS_SPOOKED;
	}
}
