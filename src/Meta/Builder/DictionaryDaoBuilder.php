<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Builder;

use Hesper\Main\DAO\SequencelessDAO;
use Hesper\Main\DAO\StorableDAO;
use Hesper\Meta\Entity\MetaClass;
use Hesper\Meta\Type\IntegerType;

/**
 * Class DictionaryDaoBuilder
 * @package Hesper\Meta\Builder
 */
final class DictionaryDaoBuilder extends BaseBuilder {

	public static function build(MetaClass $class) {
		$out = self::getHead();

		$uses = [StorableDAO::class];
		if( $class->isSequenceless() ) {
			$uses[] = SequencelessDAO::class;
		}

		$out .= <<<EOT
namespace {$class->getNamespace()}\Auto\DAO;


EOT;

		foreach($uses as $import) {
			$out .= <<<EOT
use $import;

EOT;
		}

		$out .= <<<EOT

abstract class Auto{$class->getName()}DAO extends StorableDAO
EOT;

		if($class->isSequenceless()) {
			$out .= ' implements SequencelessDAO';
		}

		$out .= <<<EOT

{

EOT;

		$pointers = self::buildPointers($class);

		$out .= <<<EOT
{$pointers}
}

EOT;

		return $out . self::getHeel();
	}
}
