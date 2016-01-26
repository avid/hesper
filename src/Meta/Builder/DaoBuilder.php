<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Builder;

use Hesper\Core\Exception\WrongStateException;
use Hesper\Meta\Entity\MetaClass;
use Hesper\Meta\Entity\MetaClassType;

/**
 * Class DaoBuilder
 * @package Hesper\Meta\Builder
 */
final class DaoBuilder extends OnceBuilder {

	public static function build(MetaClass $class) {
		$out = self::getHead();

		$out .= <<<EOT
namespace {$class->getDaoNamespace()};

use {$class->getAutoDaoClass()};


EOT;

		$type = $class->getType();

		if ($type) {
			switch ($type->getId()) {

				case MetaClassType::CLASS_ABSTRACT:

					$abstract = 'abstract ';
					$notes = 'nothing here yet';

					break;

				case MetaClassType::CLASS_FINAL:

					$abstract = 'final ';
					$notes = 'last chance for customization';

					break;

				default:

					throw new WrongStateException('unknown class type');
			}
		} else {
			$abstract = null;
			$notes = 'your brilliant stuff goes here';
		}

		$out .= <<<EOT
{$abstract}class {$class->getName()}DAO extends Auto{$class->getName()}DAO
{
	// {$notes}
}

EOT;

		return $out . self::getHeel();
	}
}
