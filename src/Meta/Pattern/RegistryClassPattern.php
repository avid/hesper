<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 */
namespace Hesper\Meta\Pattern;

use Hesper\Meta\Entity\MetaClass;
use Hesper\Meta\Entity\MetaConfiguration;
use Hesper\Meta\Console\Format;
use Hesper\Meta\Builder\RegistryClassBuilder;
use Hesper\Meta\Helper\NamespaceUtils;

/**
 * @ingroup Patterns
 **/
class RegistryClassPattern extends BasePattern {

    public function daoExists() {
        return false;
    }

    public function tableExists() {
        return false;
    }

    /**
     * @return RegistryClassPattern
     **/
    public function build(MetaClass $class) {
	    $userFile = NamespaceUtils::getBusinessPath($class, false);

        if ( MetaConfiguration::me()->isForcedGeneration() || !file_exists($userFile) ) {
            $this->dumpFile($userFile, Format::indentize(RegistryClassBuilder::build($class)));
        }

        return $this;
    }
}