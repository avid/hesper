<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Pattern;

use Hesper\Meta\Entity\MetaClass;

/**
 * Interface GenerationPattern
 * @package Hesper\Meta\Pattern
 */
interface GenerationPattern {

	/// builds everything for given class
	public function build(MetaClass $class);

	/// indicates DAO availability for classes which uses this pattern
	public function daoExists();

	/// guess what
	public function tableExists();

	/// forcing patterns to be singletones
	public static function getInstance($class /*, $args = null*/);
}
