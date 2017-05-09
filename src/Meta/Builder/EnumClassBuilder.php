<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Georgiy T. Kutsurua
 */
namespace Hesper\Meta\Builder;

use Hesper\Meta\Entity\MetaClass;
use Hesper\Meta\Helper\NamespaceUtils;

/**
 * Class EnumClassBuilder
 * @package Hesper\Meta\Builder
 */
final class EnumClassBuilder extends OnceBuilder {

	public static function build(MetaClass $class) {
		$out = self::getHead();

		$nameSpace = NamespaceUtils::getBusinessNS($class);
		$out .= <<<EOT
namespace {$nameSpace};

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
