<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Georgiy T. Kutsurua
 */
namespace Hesper\Meta\Builder;

use Hesper\Meta\Entity\MetaClass;

/**
 * Class EnumClassBuilder
 * @package Hesper\Meta\Builder
 */
final class EnumClassBuilder extends OnceBuilder {

	public static function build(MetaClass $class) {
		$out = self::getHead();

		$out .= <<<EOT
namespace {$class->getNamespace()};

use Hesper\Core\Base\Enum;


EOT;

		if ($type = $class->getType()) {
			$type = "{$type->getName()} ";
		} else {
			$type = null;
		}

		$out .= <<<EOT
{$type}class {$class->getName()} extends Enum {

	// implement me!
	protected static \$names = [];

}

EOT;

		return $out . self::getHeel();
	}
}
