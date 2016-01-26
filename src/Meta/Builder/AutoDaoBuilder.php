<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Builder;

use Hesper\Main\DAO\StorableDAO;
use Hesper\Meta\Entity\MetaClass;
use Hesper\Meta\Pattern\InternalClassPattern;

/**
 * Class AutoDaoBuilder
 * @package Hesper\Meta\Builder
 */
final class AutoDaoBuilder extends BaseBuilder {

	public static function build(MetaClass $class) {
		if (!$class->hasBuildableParent()) {
			return DictionaryDaoBuilder::build($class);
		} else {
			$parent = $class->getParent();
		}

		if ($class->getParent()->getPattern() instanceof InternalClassPattern) {
			$parentName = 'StorableDAO';
			$uses = StorableDAO::class;
		} else {
			$parentName = $parent->getName() . 'DAO';
			$uses = "{$class->getDaoNamespace()}\\{$parentName}";
		}

		$out = self::getHead();

		$out .= <<<EOT
namespace {$class->getAutoDaoNamespace()};

use $uses;


EOT;

		$out .= <<<EOT
abstract class Auto{$class->getName()}DAO extends {$parentName}
{

EOT;

		$out .= self::buildPointers($class) . "\n}\n";

		return $out . self::getHeel();
	}
}
