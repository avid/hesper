<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 */
namespace Hesper\Meta\Builder;

use Hesper\Meta\Entity\MetaClass;
use Hesper\Meta\Helper\NamespaceUtils;

/**
 * @ingroup Builders
 **/
final class RegistryClassBuilder extends OnceBuilder {
    public static function build(MetaClass $class) {
        $out = self::getHead();

	    $nameSpace = NamespaceUtils::getBusinessNS($class);
        $out .= <<<EOT
namespace {$nameSpace};

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