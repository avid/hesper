<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 */
namespace Hesper\Meta\Pattern;

use Hesper\Meta\Entity\MetaClass;

final class SpookedRegistryPattern extends RegistryClassPattern
{
	/**
	 * @return SpookedRegistryPattern
	 **/
	public function build(MetaClass $class)
	{
		return $this;
	}

	public function daoExists()
	{
		return false;
	}
}