<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Base;

/**
 * Class NamedObject
 * @see     Named
 */
abstract class NamedObject extends IdentifiableObject implements Named, Stringable {

	protected $name = null;

	public static function compareNames(Named $left, Named $right) {
		return strcasecmp($left->getName(), $right->getName());
	}

	public function getName() {
		return $this->name;
	}

	/**
	 * @return NamedObject
	 **/
	public function setName($name) {
		$this->name = $name;

		return $this;
	}

	public function toString() {
		return "[{$this->id}] {$this->name}";
	}
}
