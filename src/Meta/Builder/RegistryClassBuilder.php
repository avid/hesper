<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 */
namespace Hesper\Meta\Builder;

use Hesper\Meta\Entity\MetaClass;

/**
 * @ingroup Builders
 **/
final class RegistryClassBuilder extends OnceBuilder {
    public static function build(MetaClass $class) {
        $out = self::getHead();

        $out .= <<<EOT
namespace {$class->getNamespace()};

use Hesper\Core\Base\Registry;


EOT;
        if ($type = $class->getType())
            $type = "{$type->getName()} ";
        else
            $type = null;

        $out .= <<<EOT
{$type}class {$class->getName()} extends Registry
{
	// implement me!
	protected static \$names = array();
}

EOT;

        return $out . self::getHeel();
    }
}