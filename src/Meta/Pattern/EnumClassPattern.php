<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Georgiy T. Kutsurua
 */
namespace Hesper\Meta\Pattern;

use Hesper\Meta\Builder\EnumClassBuilder;
use Hesper\Meta\Console\Format;
use Hesper\Meta\Entity\MetaClass;
use Hesper\Meta\Entity\MetaConfiguration;

/**
 * Class EnumClassPattern
 * @package Hesper\Meta\Pattern
 */
class EnumClassPattern extends BasePattern {

	public function daoExists() {
		return false;
	}

	public function tableExists() {
		return false;
	}

	/**
	 * @return EnumClassPattern
	 **/
	public function build(MetaClass $class) {
		$userFile = HESPER_META_BUSINESS_DIR . $class->getName() . EXT_CLASS;

		if (MetaConfiguration::me()
		                     ->isForcedGeneration() || !file_exists($userFile)
		) {
			$this->dumpFile($userFile, Format::indentize(EnumClassBuilder::build($class)));
		}

		return $this;
	}
}
