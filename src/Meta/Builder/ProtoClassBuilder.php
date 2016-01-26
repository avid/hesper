<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Builder;

use Hesper\Meta\Entity\MetaClass;

/**
 * Class ProtoClassBuilder
 * @package Hesper\Meta\Builder
 */
final class ProtoClassBuilder extends OnceBuilder {

	public static function build(MetaClass $class) {
		$out = self::getHead();

		if ($type = $class->getType()) {
			$typeName = $type->toString() . ' ';
		} else {
			$typeName = null;
		}

		$out .= <<<EOT
namespace {$class->getProtoNamespace()};

use {$class->getAutoProtoClass()};


EOT;

		$out .= <<<EOT
{$typeName}class Proto{$class->getName()} extends AutoProto{$class->getName()} {/*_*/}

EOT;

		return $out . self::getHeel();
	}
}
