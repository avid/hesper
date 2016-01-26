<?php
/***************************************************************************
 *   Copyright (C) 2012 by Alexey V. Gorbylev                             *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/
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